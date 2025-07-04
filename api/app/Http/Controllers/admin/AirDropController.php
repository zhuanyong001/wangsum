<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AirDropOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AirDropController extends Controller
{
    //空投列表
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('size', 10);
        $air_drop_orders = AirDropOrder::with('currency')->orderBy('id', 'desc')->paginate($pageSize, ['*'], 'page', $page);
        return $this->success(['list' => $air_drop_orders->items(), 'total' => $air_drop_orders->total()]);
    }

    // 新增或修改
    public function save(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'order_no' => 'required|string',
            'min_usd_amount' => 'required|numeric',
            'amount_value' => 'required|numeric',
            'currency_id' => 'required|integer',
            'is_proportion' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'status' => 'required|integer',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->fail('参数错误', -1, $validator->errors());
        }

        $validatedData = $validator->validated();

        if ($id) {
            $airDropOrder = AirDropOrder::findOrFail($id);
            $airDropOrder->update($validatedData);
            return $this->success($airDropOrder);
        } else {
            $airDropOrder = AirDropOrder::create($validatedData);
            return $this->success($airDropOrder);
        }
    }
}
