<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LoanPool;
use App\Models\LoanPoolOrder;
use Illuminate\Http\Request;

class LoanPoolController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('size', 10);
        $model = LoanPool::query();

        $data = $model->orderBy('id', 'desc')->paginate($pageSize, ['*'], 'page', $page);
        $lists = $data->items();

        return $this->success(['list' => $lists, 'total' => $data->total()]);
    }
    //
    // 新增或修改
    public function save(Request $request, $id = null)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'loan_coin_ids' => 'required|array',
            'pledge_coin_ids' => 'required|array',
            'loan_ratio' => 'required|numeric',
            'loan_rate' => 'required|numeric',
            'status' => 'required|integer',
            'sort' => 'required|integer',
        ]);
        if ($id) {
            $model = LoanPool::findOrFail($id);
            $model->update($data);
            return $this->success($model);
        } else {
            $model = LoanPool::create($data);
            return $this->success($model);
        }
    }

    // 删除
    public function destroy($id)
    {
        $miningPool = LoanPool::findOrFail($id);
        $miningPool->delete();
        return $this->success($miningPool);
    }

    public function getOrderList(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('size', 10);
        $username = $request->get('username');

        $query = LoanPoolOrder::with(['user', 'currency', 'loanPool:id,name,loan_ratio,loan_rate'])->WithAdminAuth();
        if ($username) {
            $query->WithUsername($username);
        }
        $user_id = $request->get('user_id');
        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        $data = $query->orderBy('id', 'desc')->paginate($pageSize, ['*'], 'page', $page);
        $lists = $data->items();

        foreach ($lists as  $v) {
            $v->loanPool();
            $v->user();
            $v->loanCoin();
            $v->pledgeCoin();
        }
        return $this->success(['list' => $lists, 'total' => $data->total()]);
    }
}
