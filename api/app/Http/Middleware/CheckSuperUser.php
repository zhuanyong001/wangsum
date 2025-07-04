<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckSuperUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();
        // 检查用户是否已认证
        if (!$user) {
            return response()->json(['error' => '无权操作'], Response::HTTP_UNAUTHORIZED);
        }
        // 检查用户的 is_super 属性
        if ($user->is_super !== 1) {
            return response()->json(['error' => '无权操作'], Response::HTTP_FORBIDDEN);
        }
        // 通过检查，继续处理请求
        return $next($request);
    }
}
