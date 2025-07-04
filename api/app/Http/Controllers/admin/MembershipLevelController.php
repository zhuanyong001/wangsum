<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipLevel;
use Illuminate\Http\Request;

class MembershipLevelController extends Controller
{
    // 显示所有会员等级
    public function index(Request $request)
    {
        $size = $request->get('size', 10);
        $levels = MembershipLevel::paginate($size);
        return $this->success(['list' => $levels->items(), 'total' => $levels->total()]);
    }

    // 存储新的会员等级
    public function store(Request $request)
    {

        $data = $request->all();
        // 验证请求数据
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'participation_commission' => 'required|numeric',
            'equal_level_commission' => 'required|numeric',
            'pool_amount_usdt' => 'nullable|numeric',
            'direct_lower_levels' => 'nullable|numeric',
            'umbrella_people_count' => 'nullable|integer',
            'remarks' => 'nullable|string',
            'status' => 'required|boolean'
        ]);

        // 创建新的会员等级
        $level = MembershipLevel::create($request->all());

        return $this->success($level);
    }

    // 显示特定的会员等级
    public function show($id)
    {
        $level = MembershipLevel::findOrFail($id);
        return $this->success($level);
    }

    // 更新特定的会员等级
    public function update(Request $request, $id)
    {
        // 验证请求数据
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'participation_commission' => 'required|numeric',
            'equal_level_commission' => 'required|numeric',
            'pool_amount_usdt' => 'nullable|numeric',
            'direct_lower_levels' => 'nullable|numeric',
            'umbrella_people_count' => 'nullable|integer',
            'remarks' => 'nullable|string',
            'status' => 'required|boolean'
        ]);
        // 查找并更新会员等级
        $level = MembershipLevel::findOrFail($id);
        $level->update($request->all());
        return $this->success($level);
    }

    // 删除特定的会员等级
    public function destroy($id)
    {
        $level = MembershipLevel::findOrFail($id);
        $level->delete();

        return $this->success();
    }

    public function activeLevels()
    {
        var_dump(22222);
    }
}
