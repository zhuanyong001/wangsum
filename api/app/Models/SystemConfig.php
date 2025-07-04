<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{


    protected $fillable = [
        'name', 'value', 'type', 'description', 'is_private', 'category'
    ];

    // 隐藏不必要的字段
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    // 类型转换
    protected $casts = [
        'value' => 'string', // 默认转换为字符串
        //'is_private' => 'boolean'
    ];
    public static $privatelist = ['TRON_PRIVATE_KEY'];

    // 动态地根据 type 字段来转换 value
    public function getValueAttribute($value)
    {
        switch ($this->type) {
            case 'integer':
                return (int) $value;
            case 'boolean':
                return (bool) $value;
            case 'json':
                return json_decode($value, true);
            case 'float':
                return (float) $value;
            default:
                return (string) $value;
        }
    }

    public function setValueAttribute($value)
    {
        if ($this->type == 'json' && is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
    // 静态方法批量更新配置
    public static function updateConfigs(array $configs)
    {
        foreach ($configs as $name => $value) {
            $config = self::where('name', $name)->first();
            if ($config) {
                if (in_array($config->name, self::$privatelist) && $value == '******') {
                    continue;
                }
                $config->value = $value;
                $config->save();
            }
        }
        return true;
    }
    // 静态方法批量获取配置
    public static function getConfigs(array $names)
    {
        return self::whereIn('name', $names)->get()->pluck('value', 'name')->toArray();
    }
    //过滤私密配置
    public static function filterPrivateConfigs($configs)
    {

        foreach ($configs as &$config) {
            if (in_array($config->name, self::$privatelist)) {
                $config->value = '******';
            }
        }
        unset($config); // Unset reference to avoid potential side effects
        return $configs;
    }
}
