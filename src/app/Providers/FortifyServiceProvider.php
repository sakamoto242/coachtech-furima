<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        // 会員登録画面 (FN004)
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // ログイン画面 (FN007)
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // メール認証待ち画面 (FN011)
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        // ログイン制限の設定
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}