<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'nullable|integer',
            'type' => 'required|integer',
            'status' => 'required|integer',
            'sort' => 'required|integer',
            'title_langs' => 'required|array',
            'content_langs' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $notice = Notice::firstOrNew(['id' => $data['id'] ?? 0]);
            $notice->type = $data['type'];
            $notice->status = $data['status'];
            $notice->sort = $data['sort'];
            $notice->save();
            $notice->title_lang_key  = 'notice_title_' . $notice->id;
            $notice->content_lang_key = 'notice_content_' . $notice->id;
            $notice->save();
            Notice::saveToI18n($notice->title_lang_key, $data['title_langs']);
            Notice::saveToI18n($notice->content_lang_key, $data['content_langs']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '保存失败: ' . $e->getMessage()], 500);
        }
        return $this->success();
    }

    public function index(Request $request)
    {
        $size = $request->get('size', 10);
        $notices = Notice::with(['title', 'content'])->orderBy('sort', 'desc')->paginate($size);
        return $this->success(['list' => $notices->items(), 'total' => $notices->total()]);
    }


    public function details(Request $request, $id)
    {

        $notice = Notice::with(['title', 'content'])->find($id);
        if (!$notice) {
            return response()->json(['error' => 'Notice not found'], 404);
        }

        return response()->json($notice);
    }

    public function destroy(Request $request, $id)
    {
        $notice = Notice::find($id);
        if (!$notice) {
            return response()->json(['error' => 'Notice not found'], 404);
        }
        $notice->delete();
        return $this->success();
    }
}
