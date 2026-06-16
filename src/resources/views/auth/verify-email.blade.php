<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メール認証</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; }
        header { background: #000; color: #fff; padding: 15px 30px; font-weight: bold; font-size: 20px; }
        .container { text-align: center; margin-top: 80px; }
        .btn-main { display: block; margin: 30px auto; padding: 15px 40px; border: 1px solid #333; background: #ddd; color: #333; text-decoration: none; border-radius: 4px; width: 200px; font-weight: bold; }
        .btn-sub { background: none; border: none; color: #007bff; text-decoration: underline; cursor: pointer; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>
    <header>COACHTECH</header>

    <div class="container">
        <p>登録いただいたメールアドレスに認証メールを送信しました。<br>メール内のリンクから認証を完了してください。</p>
        
        <a href="http://localhost:8025" target="_blank" class="btn-main">認証はこちらから</a>

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="btn-sub">認証メールを再送信する</button>
        </form>

        @if (session('message') == 'verification-link-sent')
            <p style="color: green; margin-top: 20px;">新しい認証リンクを送信しました。</p>
        @endif
    </div>
</body>
</html>