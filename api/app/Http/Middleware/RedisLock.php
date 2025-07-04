<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RedisLock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $key, $timeout = 30)
    {
        $action = $request->route()->getAction();
        $key_id = $request->input($key) ?? $request->route($key);
        $lockKey = 'lock_' . $action['controller'] . $key . '_' .  $key_id;
        $lock = Cache::lock($lockKey, $timeout);
        // 尝试获取锁
        if ($lock->get()) {
            try {
                // 如果获取锁成功，继续处理请求
                return $next($request);
            } finally {
                // 释放锁
                $lock->release();
            }
        } else {
            // 如果无法获取锁，返回错误响应
            return response()->json(['status' => 'fail', 'message' => 'Unable to acquire lock'], 429);
        }
    }
}
