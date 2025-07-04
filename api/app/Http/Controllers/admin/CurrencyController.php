<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    //
    // 列表
    public function index(Request $request)
    {

        $page = $request->input('page', 1);
        $pageSize = $request->input('size', 10);
        $data = Currency::orderBy('sort')->paginate($pageSize, ['*'], 'page', $page);
        return $this->success(['list' => $data->items(), 'total' => $data->total()]);
    }

    // 新增或修改
    public function save(Request $request, $id = null)
    {
        $data = $request->all();

        // 设置默认值
        $data['status'] = $data['status'] ?? 1;
        $data['sort'] = $data['sort'] ?? 0;

        if ($id) {
            $currency = Currency::findOrFail($id);
            $currency->update($data);
            return $this->success($currency);
        } else {
            $currency = Currency::create($data);
            return $this->success($currency);
        }
    }

    // 显示单个币种
    public function show($id)
    {
        $currency = Currency::findOrFail($id);
        return $this->success($currency);
    }

    // 删除
    public function destroy($id)
    {
        //限制删除
        return $this->error('不允许删除');

        $currency = Currency::findOrFail($id);
        $currency->delete();
        return $this->success();
    }
}
