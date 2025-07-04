<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MiningPool;
use App\Models\MiningPoolAwardLog;
use App\Models\MiningPoolCycleItem;
use App\Models\MiningPoolOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MiningPoolController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('size', 10);
        $cate = $request->input('cate', '');
        $model = MiningPool::query();
        if ($cate) {
            $model->where('cate', $cate);
        }
        $data = $model->orderBy('id', 'desc')->paginate($pageSize, ['*'], 'page', $page);
        $lists = $data->items();
        foreach ($lists as $item) {
            $item->cycles();
        }
        return $this->success(['list' => $lists, 'total' => $data->total()]);
    }

    // 新增或修改
    public function save(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'coin' => 'required|array',
            'cycle' => 'required|array',
            'status' => 'required|integer',
            'description' => 'nullable|string',
            'sort' => 'required|integer',
            'base_total_amount' => 'nullable|numeric',
            'cate' => 'required|integer|in:1,2'
        ]);

        if ($validator->fails()) {
            return $this->fail('参数错误', -1, $validator->errors());
        }

        $validatedData = $validator->validated();

        if ($id) {
            $miningPool = MiningPool::findOrFail($id);
            //if (!$miningPool) return $this->fail('矿池不存在');
            $miningPool->update($validatedData);
            return $this->success($miningPool);
        } else {
            $miningPool = MiningPool::create($validatedData);
            return $this->success($miningPool);
        }
    }

    // 显示单个矿池
    public function show($id)
    {
        $miningPool = MiningPool::findOrFail($id);
        //if (!$miningPool) return $this->fail('矿池不存在');

        return $this->success($miningPool);
    }

    // 删除
    public function destroy($id)
    {
        $miningPool = MiningPool::findOrFail($id);
        //if (!$miningPool) return $this->fail('矿池不存在');
        $miningPool->delete();
        return $this->success($miningPool);;
    }

    public function getOrderList(Request $request)
    {
        $pageSize = $request->input('size', 10);
        $username = $request->get('username');
        $query = MiningPoolOrder::with(['user', 'currency', 'miningPool'])->WithAdminAuth();
        if ($username) {
            $query->WithUsername($username);
        }
        $user_id = $request->get('user_id');
        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        $is_internal = $request->get('is_internal', -1);
        if ($is_internal != -1) {
            $query->whereHas('user', function ($query) use ($is_internal) {
                $query->where('is_internal', $is_internal);
            });
        }


        $status = $request->get('status');
        if ($status) {
            $query->where('status', $status);
        }
        $coin_code = $request->get('coin_code');
        if ($coin_code) {
            $query->where('coin_code', $coin_code);
        }
        $cycle = $request->get('cycle');
        if ($cycle) {
            $query->where('cycle', $cycle);
        }


        $data = $query->orderBy('id', 'desc')->paginate($pageSize);
        return $this->success(['list' => $data->items(), 'total' => $data->total()]);
    }

    public function getOrderAwardList(Request $request)
    {
        $pageSize = $request->input('size', 10);

        $query =  MiningPoolAwardLog::with(['user', 'fromUser', 'miningPoolOrder'])->WithAdminAuth();
        $username = $request->get('username');
        if ($username) {
            $query->WithUsername($username);
        }
        $user_id = $request->get('user_id');
        if ($user_id) {
            $query->where('user_id', $user_id);
        }
        if ($request->get('currency_id')) {
            $query->whereHas('miningPoolOrder', function ($query) use ($request) {
                $query->where('currency_id', $request->get('currency_id'));
            });
        }

        $date = $request->get('date', []);
        if ($date && count($date) == 2) {
            $query->whereBetween('created_at', $date);
        }


        $data = $query->orderBy('id', 'desc')->paginate($pageSize);
        return $this->success(['list' => $data->items(), 'total' => $data->total()]);
    }


    public function getMiningPoolCycleItemList(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('size', 10);
        $map = [];
        $data = MiningPoolCycleItem::where($map)->paginate($pageSize, ['*'], 'page', $page);
        return $this->success(['list' => $data->items(), 'total' => $data->total()]);
    }
    public function saveMiningPoolCycleItem(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required|string',
            'days' => 'required|integer',
            'type' => 'required|integer',
            'daily_rate' => 'required|numeric',
            'compound' => 'required|integer',
            "df_currency_id" => "nullable|integer",
            "df_rate" => "nullable|numeric"
        ]);
        if ($id) {
            $miningPoolCycleItem = MiningPoolCycleItem::findOrFail($id);
            $miningPoolCycleItem->update($request->all());
            return $this->success($miningPoolCycleItem);
        } else {
            $miningPoolCycleItem = MiningPoolCycleItem::create($request->all());
            return $this->success($miningPoolCycleItem);
        }
    }

    public function destroyMiningPoolCycleItem($id)
    {
        $miningPoolCycleItem = MiningPoolCycleItem::findOrFail($id);
        $miningPoolCycleItem->delete();
        return $this->success();
    }
}
