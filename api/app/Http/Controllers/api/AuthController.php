<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{


    public function login(Request $request)
    {
        $request->validate([
            'device_id' => 'required',
        ]);

        $invite_code = Str::upper(Str::random(6));
        while (User::where('invite_code', $invite_code)->exists()) {
            // 如果邀请码已存在，重新生成
            $invite_code = Str::upper(Str::random(6));
        }

        $user = User::firstOrCreate(
            ['device_id' => $request->device_id],
            [
                'email' => $request->device_id . '@example.com',
                'password' => Hash::make('password'),
                'name' => $request->device_id,
                'invite_code' => $invite_code
            ]
        );

        $user->tokens()->delete();

        $token = $user->createToken('authToken')->plainTextToken;

        return $this->success(['user' => $user, 'token' => $token]);
    }

    public function userInfo(Request $request)
    {
        $user = $request->user();
        $redis = app('redis');
        $url = $redis->hget('config', 'invite_url');
        $user->invites_url = $url . '?code=' . $user->invite_code;
        return $this->success($request->user());
    }


    public function web3Login(Request $request)
    {
        $address = $request->input('address');
        $signature = $request->input('signature');
        $message = $request->input('message');
        $messageHex = bin2hex($message);
        $response = $this->verifySignature($address, $signature, $messageHex);

        if ($response['result'] ?? false) {  // 假设验证成功返回 {"result": true}
            // 查找或创建用户
            $user = User::firstOrCreate(
                ['tron_address' => $address],
                ['name' => $address, 'email' => $address . '@example.com', 'password' => Hash::make(Str::random(16))]
            );
            // 返回用户信息
            $user->tokens()->delete();
            $token = $user->createToken('authToken')->plainTextToken;
            $user->update(['api_token' => $token]);
            return $this->success(['user' => $user, 'token' => $token]);
        }

        return $this->fail('Verification failed');
    }
    protected function verifySignature($address, $signature, $messageHex)
    {
        $url = "https://api.trongrid.io/wallet/validateaddress";
        $data = [
            'address' => $address,
            'message' => $messageHex,
            'signature' => $signature
        ];

        $response = Http::withOptions(['verify' => false])->post($url, $data);
        return $response->json();
    }
}
