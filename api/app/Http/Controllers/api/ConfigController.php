<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\I18nLang;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    //
    public function getLangMsg(Request $request, $key = null)
    {

        $lang = I18nLang::where('key', $key)->first();
        if (!$lang) {
            return $this->fail('no lang');
        }
        //缓存

        $list = Cache::remember('lang_msg:' . $lang['key'], 6, function () use ($lang) {
            return DB::select('select * from i18n_lang_msgs M left JOIN i18n_lang_keys K on M.keyID = K.id where langID = ?', [$lang['id']]);
        });
        $lang_msgs = [];
        foreach ($list as $v) {
            $lang_msgs[$v->page][$v->key] = $v->msg;
        }
        return $lang_msgs;
    }

    public function getLangList(Request $request)
    {
        $list = I18nLang::where('status', 1)->get();

        $new_list = [];
        foreach ($list as $item) {
            $new_list[] = [
                'text' => $item->name,
                'event' => $item->key,
            ];
        }

        return $this->success($new_list);
    }


    public function getConfigs(Request $request)
    {
        $names = $request->input('names', []);
        $configs = SystemConfig::whereIn('name', $names)->where('is_private', 0)->get()->pluck('value', 'name')->toArray();
        return $this->success($configs);
    }
}
