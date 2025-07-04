<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class AppController extends Controller
{
    //
    public function getVersion()
    {


        // 发送 GET 请求
        $response = Http::withOptions([
            'verify' => false,
        ])->get('https://jk.newokpo.com/api/address/fenshen');


        // 获取响应体
        return  $response->json();
    }

    public function getAppUrl(Request $request)
    {


        //获取用户ip
        $ip = $request->getClientIp();
        $code  =  $request->get('code');
        $p_user = User::where('invite_code', $code)->first();

        $redis = app('redis');
        if ($p_user && $redis->hsetnx('l_ips', $ip, 1)) {
            $p_user->invites_count = $p_user->invites_count + 1;
            $p_user->save();
        }

        $url = $redis->hget('config', 'download_app_url');


        return $this->success([
            'url' => $url
        ]);
    }


    public function sendTestMail(Request $request)
    {
        $msg = $request->get('message', '');
        $email = $request->get('email', '');

        if (!$msg || !$email) return $this->fail();
        //判断是否是邮箱
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->fail('邮箱格式不正确', -2);
        }
        Mail::to($email)->send(new TestMail($msg));
        return $this->success();
    }
}
