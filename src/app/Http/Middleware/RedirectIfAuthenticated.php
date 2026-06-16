<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // ★ここを修正：管理者のガード（admin）でログイン済みの場合は、管理者ダッシュボードへ
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                // スタッフ（通常のユーザー）は元の設定（またはトップページ）へ
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}