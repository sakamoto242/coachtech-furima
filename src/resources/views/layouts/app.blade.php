<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

        @if (Route::is('login') || Route::is('register') || Route::is('verification.notice'))
            .navbar .d-flex.align-items-center.justify-content-end,
            .navbar form,
            .navbar .flex-grow-1 {
                display: none !important;
            }
        @endif
    </style>
</head>
<body style="background-color: #fff;">
    <nav class="navbar navbar-expand-md navbar-dark bg-black py-3">
        <div class="container-fluid px-5">
            <div class="d-flex align-items-center w-100">
                {{-- ロゴ --}}
                <div class="d-flex align-items-center" style="min-width: 200px;">
                    <a class="navbar-brand m-0" href="{{ url('/') }}">
                        <img src="{{ asset('storage/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECH" style="height: 32px; width: auto;">
                    </a>
                </div>

                <div class="flex-grow-1"></div>

                {{-- 検索バー --}}
                <div style="width: 100%; max-width: 450px;">
    <form action="{{ route('product.index') }}" method="GET">
        <input type="text" 
               class="form-control" 
               name="keyword" 
               placeholder="なにをお探しですか？" 
               value="{{ $keyword ?? '' }}">
    </form>
</div>

                <div class="flex-grow-1"></div>

               {{-- 右側メニュー --}}
<div class="d-flex align-items-center justify-content-end" style="min-width: 300px;">
    @auth
        {{-- ★マイページボタンを追加 --}}
        <a href="{{ route('mypage') }}" class="nav-link text-white fw-bold me-4">マイページ</a>

        <form action="{{ route('logout') }}" method="POST" class="m-0 me-4">
            @csrf
            <button type="submit" class="nav-link text-white fw-bold" style="background: none; border: none; padding: 0;">ログアウト</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="nav-link text-white me-4 fw-bold">ログイン</a>
        {{-- 未ログイン時は「会員登録」を表示 --}}
        <a href="{{ route('register') }}" class="nav-link text-white me-4 fw-bold">会員登録</a>
    @endauth

    <a href="{{ route('products.create') }}" class="btn btn-light fw-bold px-4">出品</a>
</div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>
</body>
</html>