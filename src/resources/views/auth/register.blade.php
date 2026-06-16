@extends('layouts.guest')

@section('content')
<style>
    .register-wrapper { width: 400px; margin: 60px auto; padding: 20px; }
    .register-wrapper h2 { text-align: center; margin-bottom: 30px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; box-sizing: border-box; }
    .btn-black { width: 100%; padding: 12px; background-color: #000; color: #fff; border: none; font-weight: bold; cursor: pointer; margin-top: 10px; }
    .login-link { text-align: center; margin-top: 20px; }
</style>
<div class="register-wrapper">
    <h2>会員登録</h2>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>名前</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>パスワード確認</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn-black">登録する</button>
    </form>
    <div class="login-link">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </div>
</div>
@endsection