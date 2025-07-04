<?php

namespace App\Http\Controllers\api;

use App\Dao\UserAssetDao;
use App\Dao\UserDao;
use App\Exceptions\ApiError;
use App\Http\Controllers\Controller;
use App\Jobs\UserLoginJobs;
use App\Models\MembershipLevel;
use App\Models\MiningPoolAwardLog;
use App\Models\MiningPoolOrder;
use App\Models\Notice;
use App\Models\TeamRelation;
use App\Models\User;
use App\Models\UserAssetLog;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $userDao;

    public function __construct(UserDao $userDao)
    {
        $this->userDao = $userDao;
    }

    public function updateBalance(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $amount = $request->input('amount');
        $description = $request->input('description', null);
        try {
            $this->userDao->updateBalance($user, $amount, $description);
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //获取用户信息
    public function getUserInfo(Request $request)
    {
        $user_id = $request->user()->id;
        $user = User::with(['assets.currency'])->find($user_id);

        $user->getMembershipLevel();
        $user->load('membership:name,level');
        foreach ($user->assets as $asset) {
            # code...
            $asset->getPoolsAssets();
        }

        return $this->success($user);
    }

    //登录
    public function web3Login(Request $request)
    {
        $address = $request->input('address');
        $signature = $request->input('signature');
        $message = $request->input('message');
        $share_code = $request->input('share_code');
        // $messageHex = bin2hex($message);


        // if (in_array($address, ['A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9-1', 'A9', 'A10'])) {
        //     $response['result'] = true;
        // } else {
        $response = $this->userDao->web3VerifySignature($address, $signature, $message);
        //  }

        if ($response['result'] ?? false) {  // 假设验证成功返回 {"result": true}
            // 查找或创建用户
            $user = $this->userDao->getUserByAddress($address);
            if ($user->status == 0) {
                return $this->fail('message.account_banned', -416);
            }
            // 返回用户信息
            $user->tokens()->delete();
            $token = $user->createToken('authToken')->plainTextToken;
            $user->update(['api_token' => $token]);
            if (!$user->referrer_id) {
                if (!$share_code) return $this->fail('message.invalid_referrer', -416);
                $inviter = User::where('share_code', $share_code)->first();
                if (!$inviter || $inviter->id == $user->id) return $this->fail('message.invalid_referrer', -416);
                DB::beginTransaction();
                try {
                    $this->userDao->createTeamRelation($inviter->id, $user->id);
                    $user->referrer_id = $inviter->id;
                    $user->save();
                    $this->userDao->createShareCode($user);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return  $this->fail('Invalid referrer', -416);
                }
            }
            dispatch(new UserLoginJobs($user, $request->ip()));
            return $this->success(['user' => $user, 'token' => $token]);
        }

        return $this->fail('Verification failed');
    }
    //

    //获取我的下级
    public function getMyTeam(Request $request)
    {
        $user = $request->user();
        $membership_levels = $request->get('levels', []);
        $times = $request->get('times', []);
        $size = $request->get('size', 10);
        //返回团队关系
        $model = TeamRelation::with('invitee')->where('inviter_id', $user->id)->where('level', 1);
        if (count($membership_levels) > 0) {
            $model = $model->whereHas('invitee', function ($query) use ($membership_levels) {
                $query->whereIn('membership_level', $membership_levels);
            });
        }
        if (count($times) > 0) {
            $model = $model->whereBetween('created_at', $times);
        }
        $team = $model->paginate($size);

        $list = $team->items();
        foreach ($list as $item) {
            $item->invitee->current_deposit_amounts = $item->invitee->miningPoolOrders()->with('currency:id,name,price')->where(['type' => 1, 'status' => MiningPoolOrder::STATUS_RUNING])->groupBy('currency_id')->selectRaw('currency_id, sum(amount) as amount')->get();
            $item->invitee->fixed_deposit_amounts = $item->invitee->miningPoolOrders()->with('currency:id,name,price')->where(['type' => 2, 'status' => MiningPoolOrder::STATUS_RUNING])->groupBy('currency_id')->selectRaw('currency_id, sum(amount) as amount')->get();
            $item->invitee->team_mining_pool_stat =    MiningPoolOrder::sumAmountByUserTeam($item->invitee->id);
            $item->invitee->team_count = $item->invitee->teamCount();
        }

        return $this->success(
            [
                'list' =>  $list,
                'total' => $team->total()
            ]
        );
    }
    //团队统计
    public function getTeamStatistics(Request $request)
    {
        $user = $request->user();
        //团队总人数
        $team_count = TeamRelation::where('inviter_id', $user->id)->count();
        //团队直属下级
        $direct = TeamRelation::where('inviter_id', $user->id)->where('level', 1)->count();

        $total_award = UserAssetLog::select(DB::raw('sum(amount) as total,user_asset_id'))->with('userAsset:id,user_id,currency_id')->where('type', UserAssetDao::TYPE_AWARD)->where('user_id', $user->id)->groupBy('user_asset_id')->get();

        $pool_amount = MiningPoolOrder::sumAmountByUserTeam($user->id);

        $total_rebate_award = MiningPoolAwardLog::sumAmountsByUserId($user->id);


        if (in_array($user->share_code, ['938586'])) {
            $team_count += 15000;
        }
        if (in_array($user->share_code, ['823293'])) {
            $team_count += 900;
        }
        if (in_array($user->share_code, ['966214'])) {
            $team_count += 350;
        }
        if (in_array($user->share_code, ['390112'])) {
            $team_count += 1000;
        }


        $l8_pool_amount = UserDao::getTeamPoolAmountUsdByDeep($user->id, 8);
        //直属激活
        $direct_active_count = UserDao::getDirectTeamActiveUserCount($user->id);
        //直属金额
        $direct_amount = UserDao::getTeamPoolAmountUsdByDeep($user->id, 1);

        return $this->success([
            'team' => $team_count,
            'direct' => $direct,
            'total_rebate_award' => $total_rebate_award,
            'total_award' => $total_award,
            'pool_amount' => $pool_amount,
            'l8_pool_amount' => $l8_pool_amount,
            'direct_amount' => $direct_amount,
            'direct_active_count' => $direct_active_count,
        ]);
    }

    //获取返佣明细

    public function getPoolsRebateLogs(Request $request)
    {
        $user = $request->user();
        $times = $request->input('times', []);
        $levels = $request->input('levels', []);
        $size = $request->input('size', 10);
        $model = MiningPoolAwardLog::where('user_id', $user->id)->whereIn('type', [2, 3]);
        if (count($times) > 0) {
            $model->whereBetween('created_at', $times);
        }
        if (count($levels) > 0) {
            $model->whereHas('fromUser', function ($query) use ($levels, $user) {
                $user_ids = TeamRelation::where('inviter_id', $user->id)->whereIn('level', $levels)->pluck('invitee_id');
                $query->whereIn('id', $user_ids);
            });
        }

        $logs = $model->with(['fromUser:id,share_code', 'miningPoolOrder:id,currency_id', 'miningPoolOrder.currency:id,name,code,price,change_24h'])->orderByDesc('id')->paginate($size);

        return $this->success(['list' => $logs->items(), 'total' => $logs->total()]);
    }



    //获取资金明细
    public function getAssetLogs(Request $request)
    {
        $types = $request->input('types', []);
        $times = $request->input('times', []);
        $currency_id = $request->input('currency_id', 0);
        $user = $request->user();
        $model = UserAssetLog::where('user_id', $user->id);
        $size = $request->input('size', 10);
        if (count($types) > 0) {
            $model->whereIn('type', $types);
        }
        if (count($times) > 0) {
            $model->whereBetween('created_at', $times);
        }
        if ($currency_id) {
            $model->whereHas('userAsset', function ($query) use ($currency_id) {
                $query->where('currency_id', $currency_id);
            });
        }
        $logs = $model->with(['userAsset:id,currency_id', 'userAsset.currency:id,name,code,price,change_24h'])->orderByDesc('id')->paginate($size);
        foreach ($logs->items() as $log) {
            $log->makeHidden('description');
        }
        return $this->success(['list' => $logs->items(), 'total' => $logs->total()]);
    }


    public function getNotices(Request $request)
    {
        $size = $request->get('size', 10);
        $notices = Notice::where('status', 1)->orderBy('sort', 'desc')->paginate($size);
        return $this->success(['list' => $notices->items(), 'total' => $notices->total()]);
    }

    public function getNodeMemberStat(Request $request)
    {

        $type = $request->input('type', 'team'); //team||direct || amount 

        $dates = $request->input('dates', []);
        $userDao = new UserDao();
        $team_ids = [];
        switch ($type) {
            case 'amount':
            case 'team':
                $team_ids = $userDao->getTeamIds($request->user()->id);
                break;
            case "direct":
                $team_ids = $userDao->getDirectTeamIds($request->user()->id);
                # code...
                break;
            default:
                return $this->fail('Invalid type');
        }
        $date_arr = [
            [
                'info_label' => 'label.today',
                'date' => [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')],
            ],
            [
                'info_label' => 'label.month',
                'date' => [date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')],
            ],
            [
                'info_label' => 'label.total',
                'date' => $dates,
                'type' => 'total',
            ],
        ]; //今日。本月。总数据

        // $team_ids = $userDao->getTeamIds($request->user()->id);

        if ($type == 'amount') {
            foreach ($date_arr as &$item) {
                //活期
                $current = $userDao->getTeamMiningPoolAmount($team_ids, $item['date'], 1);
                //定期
                $fixed =  $userDao->getTeamMiningPoolAmount($team_ids, $item['date'], 2);
                //存款
                $pool = $userDao->getTeamMiningPoolAmount($team_ids, $item['date'], 3);
                $item['data'] = [
                    ['label' => 'label.pool_current_amount', 'value' =>  round($current, 2), 'unit' => '$'],
                    ['label' => 'label.pool_fixed_amount', 'value' => round($fixed, 2), 'unit' => '$'],
                    ['label' => 'label.pool_pool_amount', 'value' => round($pool, 2), 'unit' => '$']
                ];
            }
        } else {
            foreach ($date_arr as &$item) {
                $register_count = $userDao->getTeamRegisterUserCount($team_ids, $item['date']);
                $active_count = $userDao->getTeamActiveUserCount($team_ids, $item['date']);
                $mining_pool_amount = $userDao->getTeamMiningPoolAmount($team_ids, $item['date']);
                if (isset($item['type']) && $item['type'] == 'total') {
                    $item['data'] = [
                        ['label' => 'label.register_count', 'value' => $register_count],
                        ['label' => 'label.active_count', 'value' => $active_count],
                        ['label' => 'label.mining_pool_amount', 'value' => round($mining_pool_amount, 2), 'unit' => '$']
                    ];
                } else {
                    $item['data'] = [
                        ['label' => 'label.register_count', 'value' => $register_count],
                        // ['label' => 'label.active_count', 'value' => $active_count],
                        ['label' => 'label.mining_pool_amount', 'value' => round($mining_pool_amount, 2), 'unit' => '$']
                    ];
                }
            }
        }
        return  $this->success($date_arr);
    }
}
