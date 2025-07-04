<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUploadController extends Controller
{

    /**
     * 显示上传表单（可选）
     */
    public function showUploadForm()
    {
        return view('upload_form');
    }

    /**
     * 处理图片上传
     */
    public function uploadImage(Request $request)
    {
        // 验证上传的文件
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // 处理上传的文件
        if ($request->file('file')->isValid()) {
            // 生成唯一文件名
            $fileName = time() . '.' . $request->file->extension();

            // 将文件存储在 public 目录下的 images 文件夹
            $path = $request->file->storeAs('images', $fileName, 'public');

            // 返回文件路径或其他响应
            return $this->success(['path' => '/storage/' . $path]);
        }

        return $this->fail('上传失败');
    }
}
