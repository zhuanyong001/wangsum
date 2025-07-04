<?php

// composer dump-autoload  重新生成自动加载文件

use Illuminate\Support\Facades\Log;

if (!function_exists('get_system_config')) {
    function get_system_config($name, $default = null)
    {
        try {
            $config = \App\Models\SystemConfig::where('name', $name)->first();
            return $config ? $config->value : $default;
        } catch (\Exception $e) {
            Log::error('get_system_config error', ['name' => $name, 'default' => $default, 'error' => $e->getMessage()]);
            return $default;
        }
    }
}
