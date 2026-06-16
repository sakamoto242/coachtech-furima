<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ダッシュボード</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background-color: #f2f2f2; color: #000; }
        /* ヘッダーのスタイル */
        .nav-bar { background-color: #000; color: #fff; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        .nav-logo img { height: 25px; display: block; }
        .nav-links { display: flex; gap: 20px; font-size: 14px; align-items: center; }
        .nav-links a, .nav-links button { color: #fff; text-decoration: none; background: none; border: none; font-size: 14px; cursor: pointer; padding: 0; }
        
        /* コンテンツのスタイル */
        .container { max-width: 950px; margin: 50px auto; padding: 0 20px; }
        .page-title { display: flex; align-items: center; font-size: 22px; font-weight: bold; margin-bottom: 30px; gap: 12px; }
        .page-title::before { content: ""; display: inline-block; width: 4px; height: 24px; background-color: #000; }
        .date-controller { background-color: #fff; border-radius: 6px; padding: 12px 30px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
        .date-btn { color: #767676; text-decoration: none; font-size: 14px; font-weight: bold; }
        .current-date { font-size: 16px; font-weight: bold; color: #000; }
        .table-container { background-color: #fff; border-radius: 6px; padding: 10px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
        .attendance-table { width: 100%; border-collapse: collapse; }
        .attendance-table th { font-size: 13px; color: #999; padding: 15px 20px; text-align: center; border-bottom: 1px solid #eee; }
        .attendance-table td { font-size: 14px; padding: 18px 20px; text-align: center; font-weight: bold; border-bottom: 1px solid #eee; }
        .detail-link { color: #000; text-decoration: none; font-weight: bold; }
        .detail-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<header class="nav-bar">
    <div class="nav-logo">
        <a href="{{ route('admin.dashboard') }}">
            {{-- 画像ファイル名は実際のファイル名に合わせてください --}}
            <img src="{{ asset('images/COACHTECHヘッダーロゴ (1).png') }}" alt="COACHTECH">
        </a>
    </div>
    <nav class="nav-links">
        <a href="{{ route('admin.dashboard') }}">勤怠一覧</a>
        <a href="{{ route('admin.staff.list') }}">スタッフ一覧</a>
        <a href="{{ route('admin.stamp.correction.list') }}">申請一覧</a>
        <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0; display: inline;">
            @csrf
            <button type="submit">ログアウト</button>
        </form>
    </nav>
</header>

<div class="container">
    <div class="page-title">
        {{ \Carbon\Carbon::parse($targetDate)->format('Y年n月j日') }}の勤怠
    </div>

    <div class="date-controller">
        <a href="{{ route('admin.dashboard', ['date' => \Carbon\Carbon::parse($targetDate)->subDay()->format('Y-m-d')]) }}" class="date-btn">← 前日</a>
        <div class="current-date">📅 {{ \Carbon\Carbon::parse($targetDate)->format('Y/m/d') }}</div>
        <a href="{{ route('admin.dashboard', ['date' => \Carbon\Carbon::parse($targetDate)->addDay()->format('Y-m-d')]) }}" class="date-btn">翌日 →</a>
    </div>

    <div class="table-container">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @if($attendances->isNotEmpty())
                    @foreach($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->user->name }}</td>
                            <td>{{ $attendance->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') : '---' }}</td>
                            <td>{{ $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : '---' }}</td>
                            <td>{{ $attendance->total_rest_time ?? '0:00' }}</td>
                            <td>{{ $attendance->total_work_time ?? '0:00' }}</td>
                            <td>
                                <a href="{{ route('admin.attendance.detail', $attendance->id) }}" class="detail-link">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            本日の勤怠データは登録されていません。
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

</body>
</html>