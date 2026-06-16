<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
        }
        
        /* COACHTECH共通の黒ヘッダー（ログイン前はリンクなしロゴのみ） */
        .nav-bar {
            background-color: #000;
            color: #fff;
            padding: 20px 40px;
            display: flex;
            align-items: center;
        }
        .nav-logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* ログインフォームのメインコンテナ */
        .login-container {
            max-width: 550px;
            margin: 90px auto 0 auto;
            padding: 0 20px;
            text-align: center;
        }

        /* タイトル */
        .page-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 50px;
            color: #000;
        }

        /* フォームグループ */
        .form-group {
            text-align: left;
            margin-bottom: 30px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #000;
        }

        /* 入力ボックス */
        .form-input {
            width: 100%;
            border: 1px solid #767676;
            border-radius: 4px;
            padding: 12px;
            font-size: 16px;
            box-sizing: border-box;
            background-color: #fff;
        }
        
        /* エラーメッセージ用の簡易スタイル（必要に応じて） */
        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
            font-weight: bold;
        }

        /* 見本完全準拠の黒いログインボタン */
        .btn-submit {
            width: 100%;
            background-color: #000;
            color: #fff;
            border: none;
            padding: 14px 0;
            font-size: 15px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            letter-spacing: 1px;
            transition: background-color 0.2s;
        }
        .btn-submit:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

<div class="nav-bar">
    <div class="nav-logo">COACHTECH</div>
</div>

<div class="login-container">
    
    <h1 class="page-title">管理者ログイン</h1>

    <form action="{{ route('admin.login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" class="form-input" required>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-submit">
            管理者ログインする
        </button>
    </form>
</div>

</body>
</html>