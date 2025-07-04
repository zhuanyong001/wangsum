<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\I18nLang;
use App\Models\I18nLangKey;
use App\Models\I18nLangMsg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class I18nController extends Controller
{
    //
    public function getList(Request $request)
    {
        $search = $request->get('search');
        $page = $request->get('page');
        $key = $request->get('key');

        $langlist = I18nLang::orderByDesc("id")->get();

        $query = I18nLangKey::query()
            ->leftJoin('i18n_lang_msgs as M', 'i18n_lang_keys.id', '=', 'M.keyID')
            ->selectRaw('i18n_lang_keys.id, i18n_lang_keys.page, `key` as i18nkey')
            ->groupBy('i18n_lang_keys.id', 'i18n_lang_keys.page', 'i18nkey');

        if ($search) {
            $query->whereIn('i18n_lang_keys.id', function ($subquery) use ($search) {
                $subquery->select('keyID')
                    ->from('i18n_lang_msgs')
                    ->where('msg', 'like', '%' . $search . '%');
            });
        }
        if ($key) {
            $query->where('i18n_lang_keys.key', $key);
        }


        if ($page) {
            $query->where('i18n_lang_keys.page', $page);
        }

        foreach ($langlist as $v) {
            $query->addSelect(DB::raw("MAX(CASE WHEN M.langID = {$v->id} THEN M.msg ELSE '' END) as {$v->key}"));
        }

        $list = $query->orderBy('i18n_lang_keys.page', 'asc')->get();
        $total = I18nLangKey::count();
        $pagelist = I18nLangKey::select('page')->groupBy('page')->get();

        return [
            'list' => $list,
            'total' => $total,
            'page' => $pagelist,
            'langlist' => $langlist
        ];
    }
    public function save(Request $request)
    {
        $key = $request->get('i18nkey');
        if (!$key) {
            return $this->fail('无效数据');
        }
        $id = $request->get('id');
        $page = $request->get('page');
        $is_front = $request->get('is_front');
        $keymodel = $id ? I18nLangKey::find($id) : new I18nLangKey();
        if ($key) $keymodel->key = $key;
        if ($page) $keymodel->page = $page;
        if ($is_front) $keymodel->is_front = $is_front;
        $keymodel->save();
        I18nLangMsg::where('keyID', $keymodel->id)->delete();
        $langs = I18nLang::orderByDesc("id")->get();
        foreach ($langs as $lang) {
            $content = $request->get($lang['key']);
            if ($content) {
                $model = new I18nLangMsg();
                $model->keyID = $keymodel->id;
                $model->langID = $lang->id;
                $model->msg = $content;
                $model->save();
            }
        }
        return $this->success();
    }
    public function delete(Request $request, $id = null)
    {
        //$id = $request->get('id');
        if (!$id) {
            return $this->fail("无效数据");
        }
        I18nLangMsg::where([
            'keyID' => $id,
        ])->delete();
        I18nLangKey::find($id)->delete();
        return $this->success();
    }
    public function getLang()
    {
        $langs = I18nLang::orderByDesc("id")->get();
        $res = [];
        foreach ($langs as $lang) {
            $list = DB::select('select * from i18n_lang_msgs M left JOIN i18n_lang_keys K on M.keyID = K.id where langID =' . $lang['id']);
            foreach ($list as $v) {
                $res[$lang['key']][$v->page][$v->key] = $v->msg;
            }
        }
        return $res;
    }
    public function getLangList()
    {
        $langlist = I18nLang::orderByDesc("id")->get();
        return [
            'langlist' => $langlist
        ];
    }
    public function saveLang(Request $request)
    {


        $key = $request->get('key');
        $name = $request->get('name');
        $status = $request->get('status');
        $id = $request->get('id');
        if (!$key or !$name) {
            return $this->fail("invalid data");
        }
        if ($id) {
            $lang = I18nLang::find($id);
            $lang->name = $name;
            $lang->key  = $key;
            $lang->status  = $status;

            $lang->save();
        } else {
            $lang = new I18nLang();
            $lang->name = $name;
            $lang->key  = $key;
            $lang->status  = $status;
            $lang->save();
        }
        return $this->success();
    }
    public function deletelang(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            return $this->fail("invalid data");
        }
        I18nLang::find($id)->delete();
        return $this->success();
    }
    public function setshow(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            return $this->fail("invalid data");
        }
        $lang = I18nLang::find($id);
        $lang->status = $lang->status == 1 ? 0 : 1;
        $lang->save();
        return $this->success();
    }

    public function importJson(Request $request)
    {
        $langid = $request->get('lang_id');
        if (!$langid) {
            return $this->fail("invalid data");
        }
        $json = $request->get('jsondata');
        $json = json_decode($json, true);
        if (!$json) {
            return $this->fail("invalid data");
        }

        $json_pages = $json;
        foreach ($json_pages as $page_keys => $key_keys) {
            # code...
            foreach ($key_keys as $k => $v) {
                I18nLangKey::firstOrCreate(['page' => $page_keys, 'key' => $k]);
            }
        }


        $lists = I18nLangKey::get();
        foreach ($lists as $list) {
            $lang_msg = I18nLangMsg::where(['keyID' => $list->id, 'langID' => $langid])->first();
            //var_dump($list->page."-".$list->key."-".$json[$list->page][$list->key]);
            if (isset($json[$list->page][$list->key])) {
                if ($lang_msg) {
                    $lang_msg->msg = $json[$list->page][$list->key];
                } else {
                    $lang_msg = new I18nLangMsg();
                    $lang_msg->keyID =  $list->id;
                    $lang_msg->langID = $langid;
                    $lang_msg->msg = $json[$list->page][$list->key];
                }
                $lang_msg->save();
            }
        }
        return $this->success();
    }
}
