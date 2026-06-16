@extends('layouts.app')

@section('content')
<div style="text-align: center; margin-top: 60px;">
    
    <div style="margin-bottom: 20px;">
        <span style="background: #e0e0e0; padding: 5px 15px; border-radius: 4px; font-size: 14px; color: #333;">
    @if($canEndRest) 休憩中 
    @elseif($canEndWork || $canStartRest) 出勤中
    @elseif(isset($attendance) && !is_null($attendance->end_time)) 退勤済み  @else 勤務外 @endif
</span>
    </div>

    <div style="margin-bottom: 50px;">
        <p style="font-size: 18px; color: #555; margin: 0;">{{ now()->isoFormat('YYYY年MM月DD日(ddd)') }}</p>
        <h1 style="font-size: 60px; font-weight: bold; margin: 10px 0;">{{ now()->format('H:i') }}</h1>
        
        @if(isset($attendance) && !is_null($attendance->end_time))
            <p style="font-size: 20px; color: #333; margin-top: 20px;">お疲れ様でした。</p>
        @endif
    </div>

    <div style="display: flex; justify-content: center; gap: 20px;">
        
        @if($canStartWork)
            <form action="/attendance/start" method="POST">
                @csrf
                <button type="submit" style="width: 200px; padding: 20px; background: #000; color: #fff; border: none; font-size: 20px; font-weight: bold; cursor: pointer; border-radius: 4px;">出勤</button>
            </form>
        @endif

        @if($canEndWork)
            <form action="/attendance/end" method="POST">
                @csrf
                <button type="submit" style="width: 200px; padding: 20px; background: #000; color: #fff; border: none; font-size: 20px; font-weight: bold; cursor: pointer; border-radius: 4px;">退勤</button>
            </form>
        @endif

        @if($canStartRest)
            <form action="/rest/start" method="POST">
                @csrf
                <button type="submit" style="width: 200px; padding: 20px; background: #fff; color: #000; border: 1px solid #000; font-size: 20px; font-weight: bold; cursor: pointer; border-radius: 4px;">休憩入</button>
            </form>
        @endif

        @if($canEndRest)
            <form action="/rest/end" method="POST">
                @csrf
                <button type="submit" style="width: 200px; padding: 20px; background: #fff; color: #000; border: 1px solid #000; font-size: 20px; font-weight: bold; cursor: pointer; border-radius: 4px;">休憩戻</button>
            </form>
        @endif

    </div>
</div>
@endsection