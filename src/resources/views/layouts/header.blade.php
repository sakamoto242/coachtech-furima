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
            <img src="{{ asset('images/COACHTECHヘッダーロゴ (1).png') }}" alt="COACHTECH" style="height: 25px;">
        </div>

        @if(Auth::guard('admin')->check() || Auth::check())
        <nav class="nav-links">
            @if(Auth::guard('admin')->check())
                {{-- 管理者用メニュー --}}
                <a href="{{ route('admin.dashboard') }}">勤怠一覧</a>
                <a href="{{ route('admin.staff.list') }}">スタッフ一覧</a>
                <a href="{{ route('admin.stamp.correction.list') }}">申請一覧</a>
                <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>
            @else
            {{-- スタッフ用メニュー：すべて常に表示 --}}
            <a href="{{ route('attendance.index') }}">勤怠</a>
            <a href="{{ route('attendance.list') }}">勤怠一覧</a>
            <a href="{{ route('attendance.correction.list') }}">申請</a>
            <a href="{{ route('attendance.report') }}">レポート</a>
            
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        @endif
        </nav>
        @endif
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>