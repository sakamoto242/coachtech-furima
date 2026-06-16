<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>勤怠管理アプリ</title>
    <style>
        body { font-family: sans-serif; background-color: #f2f2f2; margin: 0; padding: 0; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background: #000; color: #fff; }
        .logo { font-weight: bold; font-size: 20px; }
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links a, .nav-links button { color: #fff; text-decoration: none; font-size: 14px; background: none; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <header class="header">
    <div class="logo">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/COACHTECHヘッダーロゴ (1).png') }}" alt="COACHTECH" style="height: 25px;">
        </a>
    </div>

    
</header>
    <main>
        @yield('content')
    </main>
</body>
</html>