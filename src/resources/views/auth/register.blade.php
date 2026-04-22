@extends('layouts.app')

@section('content')
<style>
    .register-container {
        max-width: 400px;
        margin: 80px auto;
        font-family: 'Inter', sans-serif;
    }
    .register-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 30px;
        color: #333;
    }
    .card { border: none; background: transparent; }
    .card-header { display: none; }

    .form-group-custom { margin-bottom: 20px; }
    .form-group-custom label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        font-size: 14px;
    }
    .form-control-custom {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .is-invalid { border-color: #dc3545 !important; }

    .btn-register-red {
        width: 100%;
        background-color: #ff5a5f;
        color: white;
        padding: 14px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
        margin-top: 10px;
    }
    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }
    .register-footer { text-align: center; margin-top: 20px; }
</style>

<div class="register-container">
    <h1 class="register-title">会員登録</h1>

    <div class="card">
        <div class="card-body p-0">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- ユーザー名 --}}
<div class="form-group-custom">
    <label>ユーザー名</label>
    <input type="text" name="name" class="form-control-custom @error('name') is-invalid @enderror" value="{{ old('name') }}">
    @error('name')
     
        <span class="error-message"><strong>{{ $message }}</strong></span>
    @enderror
</div>

{{-- メールアドレス --}}
<div class="form-group-custom">
    <label>メールアドレス</label>
    <input type="email" name="email" class="form-control-custom @error('email') is-invalid @enderror" value="{{ old('email') }}">
    @error('email')
        <span class="error-message"><strong>{{ $message }}</strong></span>
    @enderror
</div>

{{-- パスワード --}}
<div class="form-group-custom">
    <label>パスワード</label>
    <input type="password" name="password" class="form-control-custom @error('password') is-invalid @enderror">
    @error('password')
        <span class="error-message"><strong>{{ $message }}</strong></span>
    @enderror
</div>

                {{-- 確認用パスワード --}}
                <div class="form-group-custom">
                    <label>確認用パスワード</label>
                    <input type="password" class="form-control-custom" name="password_confirmation">
                
                </div>

                <button type="submit" class="btn-register-red">
                    登録する
                </button>

                <div class="register-footer">
                    <a href="{{ route('login') }}">ログインはこちら</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection