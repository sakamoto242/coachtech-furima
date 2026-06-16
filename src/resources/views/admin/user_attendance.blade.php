<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>勤怠詳細</title>
   <style>
    header { 
        background: #000; 
        color: #fff; 
        padding: 15px 40px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
    }
    header h1 { margin: 0; font-size: 20px; }
    header nav a { color: #fff; text-decoration: none; margin-left: 20px; font-weight: bold; }
    header button { background: none; border: none; color: #fff; cursor: pointer; font-weight: bold; margin-left: 20px; }

    /* 全体背景色 */
    body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; }
    
    /* カード風のデザインにする */
    .container { 
        width: 90%; max-width: 900px; margin: 40px auto; 
        background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
    }

    /* タイトル：左側に黒い太い棒を配置 */
    .title-area { display: flex; align-items: center; margin-bottom: 25px; }
    .title-area::before { content: ""; display: block; width: 6px; height: 30px; background: #000; margin-right: 15px; }
    h2 { margin: 0; font-size: 24px; }

    /* 月移動のナビゲーション（背景グレー・中央配置） */
    .date-nav { display: flex; justify-content: center; align-items: center; gap: 40px; margin-bottom: 25px; font-weight: bold; background: #f8f8f8; padding: 15px; border-radius: 4px; }
    .date-nav a { text-decoration: none; color: #333; }

    /* テーブルデザイン */
    table { width: 100%; border-collapse: collapse; }
    th { background-color: #f8f8f8; padding: 15px; border-bottom: 2px solid #eee; text-align: center; color: #666; font-weight: normal; }
    td { padding: 15px; border-bottom: 1px solid #eee; text-align: center; color: #666; }
    
    /* CSV出力ボタン（右寄せ） */
    .csv-btn { 
        display: block; margin: 20px 0 0 auto; background: #000; color: #fff; 
        padding: 10px 25px; text-decoration: none; border-radius: 4px; width: fit-content; font-weight: bold; 
    }
</style>
</head>
<body>

<header>
    <h1>COACHTECH</h1>
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

<div class="container">
    <div class="title-area">
        <h2>{{ $user->name }} さんの勤怠</h2>
    </div>

    <div class="date-nav">
    <a href="{{ route('admin.user.attendance', ['id' => $user->id, 'month' => $prevMonth]) }}">← 前月</a>
    
    <span>📅 {{ $targetMonth }}</span>
    
    <a href="{{ route('admin.user.attendance', ['id' => $user->id, 'month' => $nextMonth]) }}">次月 →</a>
</div>

    <table>
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceList as $date => $attendance)
            <tr>
                <td>{{ \Carbon\Carbon::parse($date)->format('m/d') }}({{ ['日', '月', '火', '水', '木', '金', '土'][\Carbon\Carbon::parse($date)->dayOfWeek] }})</td>
                <td>{{ $attendance && $attendance->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') : '' }}</td>
                <td>{{ $attendance && $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : '' }}</td>
                <td>{{ $attendance ? $attendance->total_rest_time : '' }}</td>
                <td>{{ $attendance ? $attendance->total_work_time : '' }}</td>
                <td>
                    @if($attendance)
                        <a href="{{ route('admin.attendance.detail', $attendance->id) }}">詳細</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.user.attendance.csv', ['id' => $user->id, 'month' => $targetMonth]) }}" class="csv-btn">CSV出力</a>
</div>

</body>
</html>