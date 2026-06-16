@extends('layouts.app')

@section('content')
<style>
    .attendance-container { width: 90%; margin: 30px auto; }
    
    /* 見本を模したナビゲーションボックス */
    .month-nav-box { 
        background: #fff; 
        padding: 10px 20px; 
        margin: 20px auto; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        width: fit-content; 
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .month-nav-box a { text-decoration: none; color: #888; margin: 0 20px; font-weight: bold; }
    .month-nav-box span { font-weight: bold; font-size: 1.1em; color: #333; }
    .month-display {
    font-weight: bold;
    font-size: 1.1em;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px; /* マークと日付の間の隙間 */
}

    /* テーブルのスタイル */
    .attendance-table { width: 100%; border-collapse: collapse; background: #fff; }
    .attendance-table th { background-color: #fff; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; padding: 15px; text-align: center; }
    .attendance-table td { padding: 15px; text-align: center; border-bottom: 1px solid #eee; }
</style>

<div class="attendance-container">
    <h2>勤怠一覧</h2>

    <div class="month-nav-box">
    <a href="{{ route('attendance.list', ['month' => $prevMonth]) }}">← 前月</a>
    
    <span class="month-display">
        📅 {{ $targetMonth }}
    </span>
    
    <a href="{{ route('attendance.list', ['month' => $nextMonth]) }}">翌月 →</a>
</div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>日付</th><th>出勤</th><th>退勤</th><th>休憩</th><th>合計</th><th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceList as $date => $attendance)
            <tr>
                <td>{{ \Carbon\Carbon::parse($date)->translatedFormat('m/d(D)') }}</td>
                @if($attendance)
                    <td>{{ $attendance->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->total_rest_time }}</td>
                    <td>{{ $attendance->total_work_time }}</td>
                    <td><a href="{{ route('attendance.detail', $attendance->id) }}">詳細</a></td>
                @else
                    <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection