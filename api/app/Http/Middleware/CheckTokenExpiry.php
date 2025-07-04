<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckTokenExpiry
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // 假设使用 created_at 来记录令牌创建时间
        $tokenCreatedAt = $user->tokens()->where('name', 'admin-token')->first()->created_at;
        $time =  $user->is_super ? 2 : 24;
        if ($tokenCreatedAt->diffInHours(now()) >= $time) { // 24小时失效
            $user->tokens()->delete();
            return response()->json(['error' => 'Token has expired.'], 401);
        }

        return $next($request);
    }
}
