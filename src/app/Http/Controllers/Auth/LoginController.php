<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // ここでリダイレクト先をトップページに設定
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * ログイン失敗時のメッセージ設定
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['ログイン情報が登録されていません'],
        ]);
    }

    /**
     * ログアウト時のリダイレクト先
     */
    protected function loggedOut(Request $request)
    {
        return redirect('/');
    }

    // ★ ここにあった authenticated メソッドを削除しました
}