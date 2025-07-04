<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    /**
     *  @param string $key //对应的多语言key
     *  @param Array $contents //内容对应的多语言{en:aa,zh:bb}
     * 
     */

    public static function saveToI18n($key, $contents)
    {
        DB::beginTransaction();
        try {
            $keymodel = I18nLangKey::firstOrCreate(['key' => $key, 'page' => 'notice']);
            $langs = I18nLang::orderByDesc("id")->get();
            foreach ($langs as $lang) {
                if (isset($contents[$lang['key']])) {
                    I18nLangMsg::where(['keyID' => $keymodel->id, 'langID' => $lang->id])->delete();
                    $model = new I18nLangMsg();
                    $model->keyID = $keymodel->id;
                    $model->langID = $lang->id;
                    $model->msg = $contents[$lang['key']];
                    $model->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function title()
    {
        return $this->hasOne(I18nLangKey::class, 'key', 'title_lang_key')->with('msgs.lang');
    }
    public function content()
    {
        return $this->hasOne(I18nLangKey::class, 'key', 'content_lang_key')->with('msgs.lang');
    }
}
