{{-- resources/views/attendance/correction_detail.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container" style="width: 90%; margin: 30px auto;">
    <h2>修正申請詳細</h2>
    
    <div style="background: #fff; padding: 20px; border: 1px solid #ddd;">
        <p><strong>対象日時:</strong> {{ $requestData->attendance->date }}</p>
        <p><strong>申請理由:</strong> {{ $requestData->reason }}</p>
        <p><strong>申請状況:</strong> {{ $requestData->status }}</p>
        
        <a href="{{ route('attendance.correction.list') }}" style="display: inline-block; margin-top: 20px;">一覧に戻る</a>
    </div>
</div>
@endsection