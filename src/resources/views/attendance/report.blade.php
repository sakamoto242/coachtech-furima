@extends('layouts.app')

@section('content')
<style>
    .container { width: 80%; margin: 40px auto; }
    .header-title { font-size: 20px; font-weight: bold; margin-bottom: 20px; }
    .summary-cards { display: flex; gap: 20px; margin-bottom: 40px; }
    .card { background: #fff; padding: 20px; border-radius: 8px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
    .card strong { font-size: 1.2em; }
    .table-container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 15px; border-bottom: 2px solid #eee; }
    td { padding: 15px; border-bottom: 1px solid #eee; }
    .anomaly-section { margin-top: 40px; }
</style>

<div class="container">
    <div class="header-title">マイ勤怠レポート</div>
    <p>過去6ヶ月の勤怠データから集計しています。</p>

    <div class="summary-cards">
        <div class="card">総労働時間<br><strong>{{ $totalWorkFormatted }}</strong></div>
        <div class="card">総残業時間<br><strong>{{ $totalOverFormatted }}</strong></div>
        <div class="card">平均労働時間 / 日<br><strong>{{ $avgWorkFormatted }}</strong></div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr><th>月</th><th>労働時間</th><th>残業時間</th></tr>
            </thead>
            <tbody>
                @foreach($reportData as $data)
                <tr>
                    <td>{{ $data['month'] }}</td>
                    <td>{{ $data['work_time'] }}</td>
                    <td>{{ $data['over_time'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="anomaly-section">
        <h3>今月の異常検知</h3>
        <p>基準：始業 09:00 / 終業 18:00 / 長時間労働は 1日 10 時間超</p>
        <div class="summary-cards">
            <div class="card">遅刻回数<br><strong>{{ $lateCount }} 回</strong></div>
            <div class="card">早退回数<br><strong>{{ $earlyLeaveCount }} 回</strong></div>
            <div class="card">長時間労働日数<br><strong>{{ $overtimeDaysCount }} 日</strong></div>
        </div>
    </div>
</div>
@endsection