@extends('layouts.guest')

@section('content')
<div style="max-width: 400px; margin: 80px auto; text-align: center;">
    <h2 style="margin-bottom: 30px; font-size: 24px;">ログイン</h2>
    
    <div style="background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div style="text-align: left; margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div style="text-align: left; margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">パスワード</label>
                <input type="password" name="password" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>

            <button type="submit" style="width: 100%; padding: 15px; background-color: #000; color: #fff; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer;">
                ログインする
            </button>
        </form>
    </div>
    
    <div style="margin-top: 20px;">
        <a href="{{ route('register') }}" style="color: #007bff; text-decoration: none; font-size: 14px;">会員登録はこちら</a>
    </div>
</div>
@endsection