<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemConfigController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('size', 10);
        $data = DB::table('system_configs')->orderBy('id', 'desc')->paginate($pageSize, ['*'], 'page', $page);
        $configs = $data->items();
        $configs = SystemConfig::filterPrivateConfigs($configs);

        return $this->success([
            "list" =>  $configs,
            'total' => $data->total()
        ]);
    }

    public function show($id)
    {
        $config = SystemConfig::find($id);
        if (!$config) {
            return $this->fail('Config not found');
        }
        return $this->success($config);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:system_configs',
            'value' => 'required',
            'type' => 'required|in:string,integer,boolean,json,float',
            'description' => 'nullable|string',
            'is_private' => 'sometimes|boolean'
        ]);

        $config = SystemConfig::create($validated);
        return $this->success($config);
    }

    public function update(Request $request, $id)
    {
        $config = SystemConfig::find($id);
        if (!$config) {
            return $this->success('Config not found');
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:system_configs,name,' . $id,
            'value' => 'required',
            'type' => 'required|in:string,integer,boolean,json,float,image',
            'description' => 'nullable|string',
            'is_private' => 'sometimes|boolean',
            'category' => 'nullable|string'
        ]);

        $config->update($validated);
        return $this->success($config);
    }

    public function destroy($id)
    {
        $config = SystemConfig::find($id);
        if (!$config) {
            return response()->json(['error' => 'Config not found'], 404);
        }

        $config->delete();
        return response()->json(['message' => 'Config deleted successfully']);
    }

    /**
     * 修改多个配置
     * 请求示例：
     * "configs": [
     *    {"name": "site_name", "value": "My Updated Site Name"},
     *    {"name": "admin_email", "value": "admin@example.com"}
     *  ]
     * @param Request $request
     * @return void
     */
    public function updateConfigs(Request $request)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*.name' => 'required|string|exists:system_configs,name',
            'configs.*.value' => 'required',
        ]);
        $configs = collect($validated['configs'])->mapWithKeys(function ($item) {
            return [$item['name'] => $item['value']];
        })->toArray();
        SystemConfig::updateConfigs($configs);

        return $this->success();
    }

    /**获取多个配置
     * 
     */
    public function getConfigs(Request $request)
    {
        $validated = $request->validate([
            'names' => 'required|array',
            'names.*' => 'required|string|exists:system_configs,name',
        ]);
        $configs = SystemConfig::getConfigs($validated['names']);
        foreach ($configs as $key => $value) {
            if (in_array($key, SystemConfig::$privatelist)) {
                $configs[$key] = '******';
            }
        }

        return $this->success($configs);
    }
    /**
     * 获取分类
     */
    public function getCategories()
    {
        $categories = SystemConfig::whereNotNull('category')->groupBy('category')->pluck('category');
        return $this->success($categories);
    }

    /**
     * 根据分类获取配置
     */
    public function getConfigsByCategory(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|exists:system_configs,category',
        ]);

        $configs = DB::table('system_configs')->where('category', $validated['category'])->get();

        $configs = SystemConfig::filterPrivateConfigs($configs);
        foreach ($configs as $config) {
            if ($config->type == 'boolean') {
                $config->value = (int)$config->value;
            }
        }
        return $this->success($configs);
    }
}
