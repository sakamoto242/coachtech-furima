@extends('layouts.app')

@section('content')
<style>
    /* 全体を中央に寄せるレイアウト */
    .verify-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 70vh; /* 画面中央に配置するための高さ */
        text-align: center;
        font-family: 'Inter', sans-serif;
    }

    /* メッセージテキスト */
    .verify-message {
        font-size: 18px;
        font-weight: bold;
        line-height: 1.6;
        margin-bottom: 40px;
        color: #333;
    }

    .btn-verify-gray {
        display: inline-block;
        background-color: #e0e0e0; /* 薄いグレー */
        color: #333;
        padding: 12px 60px;
        border: 1px solid #999;
        border-radius: 8px;
        font-size: 18px;
        font-weight: bold;
        text-decoration: none;
        cursor: pointer;
        margin-bottom: 30px;
        transition: background-color 0.2s;
    }

    .btn-verify-gray:hover {
        background-color: #d0d0d0;
        color: #333;
    }

  
    .resend-link {
        background: none;
        border: none;
        color: #007bff;
        text-decoration: underline;
        font-size: 14px;
        cursor: pointer;
        padding: 0;
    }

  
    .alert-success {
        position: fixed;
        top: 100px;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }
</style>

<div class="verify-wrapper">
    {{-- メッセージ部分 --}}
    <p class="verify-message">
        登録していただいたメールアドレスに認証メールを送信しました。<br>
        メール認証を完了してください。
    </p>

    {{-- ステータスメッセージ --}}
    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            新しい確認リンクが送信されました。
        </div>
    @endif
{{-- メインの「認証はこちらから」ボタン --}}
    {{-- 修正：mailto ではなく、直接 MailHog の URL を指定します --}}
<a href="http://localhost:8025" target="_blank" class="btn-verify-gray" style="text-decoration: none;">
    認証はこちらから
</a>

    {{-- 再送フォーム --}}
    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="resend-link">
            認証メールを再送する
        </button>
    </form>
</div>
@endsection