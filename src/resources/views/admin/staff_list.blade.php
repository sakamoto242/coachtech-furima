<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>スタッフ一覧</title>
    <style>
        /* 全体とヘッダー設定 */
        body { font-family: sans-serif; margin: 0; background-color: #f4f4f4; }
        header { background: #000; color: #fff; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        .logo img { height: 25px; display: block; }
        header nav { display: flex; align-items: center; }
        header nav a { color: #fff; text-decoration: none; margin-left: 20px; font-weight: bold; font-size: 14px; }
        header button { background: none; border: none; color: #fff; cursor: pointer; font-weight: bold; margin-left: 20px; font-size: 14px; }

        /* タイトル：左側に黒い棒を配置 */
        .title-area { padding: 40px 0 20px 40px; display: flex; align-items: center; }
        .title-area::before { content: ""; display: block; width: 6px; height: 30px; background: #000; margin-right: 15px; }
        h2 { margin: 0; font-size: 24px; }

        /* テーブルを見本に合わせる */
        table { width: 90%; margin: 0 auto; border-collapse: collapse; background: #fff; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th { background-color: #f8f8f8; padding: 15px 20px; color: #333; font-weight: bold; text-align: center; border-bottom: 1px solid #eee; }
        td { padding: 15px 20px; text-align: center; border-bottom: 1px solid #eee; }
        
        /* リンクの装飾 */
        .action-link { color: #000; font-weight: bold; text-decoration: none; }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/COACHTECHヘッダーロゴ (1).png') }}" alt="COACHTECH">
        </a>
    </div>
    <nav>
        <a href="{{ route('admin.dashboard') }}">勤怠一覧</a>
        <a href="{{ route('admin.staff.list') }}">スタッフ一覧</a>
        <a href="{{ route('admin.stamp.correction.list') }}">申請一覧</a>
        <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">ログアウト</button>
        </form>
    </nav>
</header>

<div class="title-area">
    <h2>スタッフ一覧</h2>
</div>

<table>
    <thead>
        <tr>
            <th>名前</th>
            <th>メールアドレス</th>
            <th>月次勤怠</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <a href="{{ route('admin.user.attendance', ['id' => $user->id]) }}" class="action-link">詳細</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>