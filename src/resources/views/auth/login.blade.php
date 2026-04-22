@extends('layouts.app')

@section('content')
<style>
    /* 全体を中央に寄せるレイアウト */
    .login-container {
        max-width: 400px;
        margin: 80px auto;
        font-family: 'Inter', sans-serif;
    }

    /* 「ログイン」タイトル */
    .login-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 30px;
        color: #333;
    }

    .card {
        border: none;
        background: transparent;
    }
    .card-header {
        display: none;
    }


    .form-group-custom {
        margin-bottom: 25px;
    }

    .form-group-custom label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        font-size: 14px;
        color: #333;
    }

    /* 入力欄のデザイン */
    .form-control-custom {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        box-sizing: border-box;
    }

    .btn-login-red {
        width: 100%;
        background-color: #ff5a5f;
        color: white;
        padding: 14px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 10px;
        transition: opacity 0.2s;
    }

    .btn-login-red:hover {
        opacity: 0.8;
        color: white;
    }

    .login-footer {
        text-align: center;
        margin-top: 20px;
    }
    .login-footer a {
        color: #007bff;
        text-decoration: none;
        font-size: 14px;
    }
</style>

<div class="login-container">
    <h1 class="login-title">ログイン</h1>

    <div class="card">
        <div class="card-body p-0">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- メールアドレス --}}
                <div class="form-group-custom">
                    <label>メールアドレス</label>
                    <input id="email" type="email" class="form-control-custom @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                    @error('email')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- パスワード --}}
                <div class="form-group-custom">
                    <label>パスワード</label>
                 <input id="password" type="password" class="form-control-custom @error('password') is-invalid @enderror" name="password" autocomplete="current-password">
                    @error('password')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-login-red">
                    ログインする
                </button>

                <div class="login-footer">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">会員登録はこちら</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection