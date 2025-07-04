<?php

namespace App\Http\Controllers\admin;

use App\Dao\UserAssetDao;
use App\Dao\UserDao;
use App\Http\Controllers\Controller;
use App\Models\TeamRelation;
use App\Models\User;
use App\Models\UserAsset;
use App\Models\UserStateLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getMemberList(Request $request)
    {
        $size = $request->get('size', 20);
        $tron_address = $request->get('tron_address', '');
        $query = User::with(['assets.currency', 'membership', 'lastLoginIp'])->WithAdminAuth();
        $is_internal = $request->get('is_internal', -1);
        if ($is_internal != -1) {
            $query->where('is_internal', $is_internal);
        }

        $map = [];
        if ($tron_address) {
            $query->where(function ($query) use ($tron_address) {
                $query->where('tron_address', 'like', "%$tron_address%")
                    ->orWhere('name', 'like', "%$tron_address%")
                    ->orWhere('share_code', 'like', "%$tron_address%");
            });
        }
        $data = $query->where($map)->orderByDesc('id')->paginate($size);

        $list = $data->items();
        foreach ($list as $item) {
            $item->makeVisible('remark');
            $item->getMembershipLevel();
        }
        return $this->success(['list' => $list, 'total' => $data->total()]);
    }

    public function show($id, UserDao $userDao)
    {
        $user = User::findOrFail($id)->makeVisible('remark');
        //按层级 统计下级数量
        $userDao->getUserInfo($user);
        return $this->success($user);
    }

    public function getUserInfoByAddress(UserDao $userDao, Request $request)
    {
        $address = $request->get('address');
        $user = User::where('tron_address', $address)->first();
        if (!$user) {
            return $this->error('用户不存在');
        }
        $userDao->getUserInfo($user);
        return $this->success($user);
    }


    /**
     * 获取上级代理
     */
    public function getUpAgent(Request $request)
    {
        $user_id = $request->get('user_id');
        $agents = TeamRelation::with(['inviter.assets.currency', 'inviter.membership'])->where('invitee_id', $user_id)->get();
        return $this->success(['list' => $agents, 'count' => count($agents)]);
    }

    /**
     * 获取下级代理
     */
    public function getDownAgent(Request $request)
    {
        $user_id = $request->get('user_id');
        $level = $request->get('level', 0);
        $size = $request->get('size', 2000);
        $query = TeamRelation::with('invitee.assets.currency')->where('inviter_id', $user_id);
        if ($level) {
            $query->where('level', $level);
        }
        $lists = $query->paginate($size);
        $agents = $lists->items();
        foreach ($agents as $item) {
            $item->l1_count = TeamRelation::where('inviter_id', $item->invitee_id)->where('level', 1)->count();
            if ($item->l1_count) {
                $item->children = [];
            }
        }
        return $this->success([
            'list' => $agents,
            'total' => $lists->total()
        ]);
    }

    /**
     * 充值资产
     */
    public function depositAssets(Request $request, UserAssetDao $UserAssetDao)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'currency_id' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        $user_id = $request->get('user_id');
        $currency_id = $request->get('currency_id');
        $amount = $request->get('amount');
        $user = User::findOrFail($user_id);
        $userAsset =  UserAsset::firstOrCreate(['user_id' => $user->id, 'currency_id' => $currency_id], ['amount' => 0]);
        $UserAssetDao->updateUserAsset($userAsset, $amount, UserAssetDao::TYPE_ADMIN_RECHARGE, '后台添加');
        return $this->success();
    }

    /**
     * 冻结用户
     */
    public function freezeUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'type' => 'required|in:1,0'
        ]);
        $user_id = $request->get('user_id');
        $type = $request->get('type');
        $user = User::findOrFail($user_id);
        $user->status = $type == 0 ? User::STATUS_NORMAL : User::STATUS_FROZEN;
        $user->save();
        $user->tokens()->delete();
        return $this->success();
    }

    /**
     * 兑换开关
     */
    public function setCanExchange(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'type' => 'required|in:1,0'
        ]);
        $user_id = $request->get('user_id');
        $type = $request->get('type');
        $user = User::findOrFail($user_id);
        $user->can_exchange = $type == 0 ? 0 : 1;
        $user->save();

        return $this->success();
    }
    /*
    * 各类状态改变
    */
    public function changeState(Request $request, $state)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'type' => 'required|in:1,0'
        ]);
        $states = [
            'is_internal' => 'is_internal',
            'can_exchange' => 'can_exchange',
            'is_ln_rebate' => 'is_ln_rebate'
        ];
        if (!isset($states[$state])) {
            return $this->fail('状态不存在');
        }
        $user_id = $request->get('user_id');
        $type = $request->get('type');
        $user = User::findOrFail($user_id);

        $oldValue = $user->{$state};
        $newValue = $type == 0 ? 0 : 1;

        // 更新用户状态
        $user->{$state} = $newValue;
        $user->save();

        // 记录日志
        UserStateLog::create([
            'user_id' => $user_id,
            'state' => $state,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'changed_by' => auth()->id() // 假设有登录用户
        ]);

        return $this->success();
    }


    /**
     * 设置内部账号
     * 
     */
    public function setInternalAccount(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'type' => 'required|in:1,0'
        ]);
        $user_id = $request->get('user_id');
        $type = $request->get('type');
        $user = User::findOrFail($user_id);
        $user->is_internal = $type;
        $user->save();
        return $this->success();
    }
    /**
     * 添加内部号 
     */
    public function addInternalAccount(Request $request, UserDao $userDao)
    {
        $data = $request->validate([
            'address' => 'required|string',
            "share_code" => 'nullable|string'
        ]);
        $user = $userDao->getUserByAddress($data['address']);
        $user->is_internal = 1;
        $user->save();
        $userDao->createShareCode($user);
        $share_code = $data['share_code'] ?? null;
        if ($share_code && $user->referrer_id == null) {
            $inviter = User::where('share_code', $share_code)->first();
            if (!$inviter || $inviter->id == $user->id) return $this->fail('邀请人不存在');
            DB::beginTransaction();
            try {
                $userDao->createTeamRelation($inviter->id, $user->id);
                $user->referrer_id = $inviter->id;
                $user->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->fail($e->getMessage());
            }
        }

        return $this->success();
    }

    /**
     * 设置等级
     */
    public function setMembershipLevel(Request $request)
    {
        $data =  $request->validate([
            'user_id' => 'required|integer',
            'level' => 'required|integer'
        ]);
        $user = User::findOrFail($data['user_id']);
        $user->pre_level = $data['level'];
        $user->save();
        return $this->success();
    }

    /**
     * 设置备注
     */
    public function setRemark(Request $request)
    {
        $data =  $request->validate([
            'user_id' => 'required|integer',
            'remark' => 'required|string'
        ]);
        $user = User::findOrFail($data['user_id']);
        $user->remark = $data['remark'];
        $user->save();
        return $this->success();
    }
}
