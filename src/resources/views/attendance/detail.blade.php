<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>勤怠詳細</title>
    <style>
        body { font-family: sans-serif; background-color: #f2f2f2; margin: 0; }
        .nav-bar { background-color: #000; color: #fff; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        .nav-logo { font-size: 20px; font-weight: bold; }
        .nav-links { display: flex; gap: 20px; font-size: 14px; align-items: center; }
        .nav-links a, .nav-links button { color: #fff; text-decoration: none; background: none; border: none; font-size: 14px; cursor: pointer; }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; }
        .page-title { font-size: 22px; font-weight: bold; margin-bottom: 30px; display: flex; align-items: center; }
        .page-title::before { content: ""; display: inline-block; width: 4px; height: 24px; background-color: #000; margin-right: 12px; }
        .detail-card { background-color: #fff; border-radius: 6px; padding: 10px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .form-row { display: flex; padding: 20px 40px; border-bottom: 1px solid #eee; align-items: center; }
        .row-label { width: 30%; color: #999; font-weight: bold; font-size: 14px; }
        .row-content { width: 70%; font-weight: bold; font-size: 15px; }
        input[type="time"]::-webkit-calendar-picker-indicator { display: none; }
        input[type="time"] { border: 1px solid #ddd; padding: 5px; border-radius: 4px; text-align: center; width: 90px; }
        textarea { width: 100%; height: 80px; border: 1px solid #ddd; padding: 10px; border-radius: 4px; box-sizing: border-box; }
        .btn-container { text-align: center; padding: 30px; }
        .btn-black { background-color: #000; color: #fff; padding: 10px 50px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .status-msg { text-align: center; padding: 30px; color: #ff0000; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>

<nav class="nav-bar">
    <div class="nav-logo">COACHTECH</div>
    <div class="nav-links">
        <a href="{{ route('attendance.index') }}">勤怠</a>
        <a href="{{ route('attendance.list') }}">勤怠一覧</a>
        <a href="{{ route('attendance.correction.list') }}">申請</a>
        <a href="{{ route('attendance.report') }}">レポート</a>
        <form action="/logout" method="POST" style="margin: 0;">
            @csrf
            <button type="submit">ログアウト</button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="page-title">勤怠詳細</div>

    <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
        @csrf
        <div class="detail-card">
            <div class="form-row"><div class="row-label">名前</div><div class="row-content">{{ $attendance->user->name }}</div></div>
            <div class="form-row"><div class="row-label">日付</div><div class="row-content">{{ \Carbon\Carbon::parse($attendance->date)->format('Y年 n月j日') }}</div></div>
            
            <div class="form-row">
                <div class="row-label">出勤・退勤</div>
                <div class="row-content">
                    <input type="time" name="start_time" value="{{ isset($activeRequest) ? substr($activeRequest->requested_start_time, 0, 5) : substr($attendance->start_time, 0, 5) }}" {{ isset($activeRequest) ? 'disabled' : '' }}> 〜 
                    <input type="time" name="end_time" value="{{ isset($activeRequest) ? substr($activeRequest->requested_end_time, 0, 5) : ($attendance->end_time ? substr($attendance->end_time, 0, 5) : '') }}" {{ isset($activeRequest) ? 'disabled' : '' }}>
                </div>
            </div>

   <div class="form-row">
    <div class="row-label">休憩</div>
    <div class="row-content">
        <input type="time" name="rest_start_1" 
               @if(isset($activeRequest) && $activeRequest->requested_rest_start_1) value="{{ substr($activeRequest->requested_rest_start_1, 0, 5) }}" @elseif(isset($attendance->rests[0])) value="{{ substr($attendance->rests[0]->start_time, 0, 5) }}" @endif {{ isset($activeRequest) ? 'disabled' : '' }}> 〜 
        <input type="time" name="rest_end_1" 
               @if(isset($activeRequest) && $activeRequest->requested_rest_end_1) value="{{ substr($activeRequest->requested_rest_end_1, 0, 5) }}" @elseif(isset($attendance->rests[0])) value="{{ substr($attendance->rests[0]->end_time, 0, 5) }}" @endif {{ isset($activeRequest) ? 'disabled' : '' }}>
    </div>
</div>

<div class="form-row">
    <div class="row-label">休憩2</div>
    <div class="row-content">
        <input type="time" name="rest_start_2" 
               @if(isset($activeRequest) && $activeRequest->requested_rest_start_2) value="{{ substr($activeRequest->requested_rest_start_2, 0, 5) }}" @elseif(isset($attendance->rests[1])) value="{{ substr($attendance->rests[1]->start_time, 0, 5) }}" @endif {{ isset($activeRequest) ? 'disabled' : '' }}> 〜 
        <input type="time" name="rest_end_2" 
               @if(isset($activeRequest) && $activeRequest->requested_rest_end_2) value="{{ substr($activeRequest->requested_rest_end_2, 0, 5) }}" @elseif(isset($attendance->rests[1])) value="{{ substr($attendance->rests[1]->end_time, 0, 5) }}" @endif {{ isset($activeRequest) ? 'disabled' : '' }}>
    </div>
</div>

            <div class="form-row">
                <div class="row-label">備考</div>
                <div class="row-content">
                    <textarea name="reason" {{ isset($activeRequest) ? 'disabled' : '' }}>{{ isset($activeRequest) ? $activeRequest->reason : $attendance->reason }}</textarea>
                </div>
            </div>

            @if(isset($activeRequest))
                <div class="status-msg">＊承認待ちのため修正はできません。</div>
            @else
                <div class="btn-container">
                    <button type="submit" class="btn-black">修正</button>
                </div>
            @endif
        </div>
    </form>
</div>
</body>
</html>