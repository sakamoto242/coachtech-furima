@extends('layouts.app')

@section('content')
<style>
    .container { max-width: 700px; margin: 40px auto; padding: 20px; }
    .detail-card { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .attendance-table { width: 100%; border-collapse: separate; border-spacing: 0 20px; }
    .attendance-table th { text-align: left; width: 30%; color: #555; }
    .attendance-table td { padding: 5px 0; }
    .btn-container { text-align: right; margin-top: 30px; }
    .btn-black { background: #555; color: #fff; padding: 10px 30px; border: none; cursor: pointer; }
    .btn-black:disabled { background: #aaa; cursor: not-allowed; }
</style>

<div class="container">
    <h2>{{ isset($correctionRequest) ? '勤怠修正承認' : '勤怠詳細' }}</h2>
    
    <div class="detail-card">
        {{-- 申請がある場合は承認フォーム、ない場合は通常の更新フォームへ分岐させるなどの制御が必要 --}}
        <form action="{{ isset($correctionRequest) ? route('admin.approve', $correctionRequest->id) : route('admin.attendance.update', $attendance->id) }}" method="POST">
            @csrf
            @if(isset($correctionRequest))
                <input type="hidden" name="action" value="approve">
            @endif

            <table class="attendance-table">
                <tr><th>名前</th><td>{{ $attendance->user->name }}</td></tr>
                <tr><th>日付</th><td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y年 m月d日') }}</td></tr>
                
                @if(isset($correctionRequest))
                    {{-- 申請ベースの表示 --}}
                    <tr>
                        <th>出勤・退勤</th>
                        <td>
                            {{ \Carbon\Carbon::parse($correctionRequest->requested_start_time)->format('H:i') }} 〜 
                            {{ \Carbon\Carbon::parse($correctionRequest->requested_end_time)->format('H:i') }}
                        </td>
                    </tr>
                    <tr>
                        <th>備考</th><td>{{ $correctionRequest->reason }}</td>
                    </tr>
                @else
                    {{-- 通常の勤怠表示（編集可能にする場合はinputタグにする） --}}
                    <tr>
                        <th>出勤・退勤</th>
                        <td>
                            {{ $attendance->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') : '--:--' }} 〜 
                            {{ $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : '--:--' }}
                        </td>
                    </tr>
                    <tr><th>備考</th><td>{{ $attendance->remarks }}</td></tr>
                @endif
            </table>

            <div class="btn-container">
                @if(isset($correctionRequest))
                    @if($correctionRequest->status === 'approved')
                        <button type="button" class="btn-black" disabled>承認済み</button>
                    @else
                        <button type="submit" class="btn-black">承認</button>
                    @endif
                @endif
            </div>
        </form>
    </div>
</div>
@endsection