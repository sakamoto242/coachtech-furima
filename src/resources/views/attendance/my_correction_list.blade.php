@extends('layouts.app')

@section('content')
<style>
    .container { width: 90%; margin: 30px auto; }
    h2 { margin-bottom: 20px; }
    .tab-menu { margin-bottom: 20px; border-bottom: 1px solid #ddd; }
    .tab-menu a { padding: 10px 20px; text-decoration: none; color: #333; display: inline-block; }
    .tab-menu a.active { border-bottom: 2px solid #000; font-weight: bold; }
    .correction-table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .correction-table th { background-color: #f8f8f8; padding: 15px; text-align: center; border-bottom: 1px solid #ddd; }
    .correction-table td { padding: 15px; text-align: center; border-bottom: 1px solid #eee; }
</style>

<div class="container">
    <h2>申請一覧</h2>

    <div class="tab-menu">
        <a href="{{ route('attendance.correction.list', ['tab' => 'pending']) }}" 
           class="{{ $tab === 'pending' ? 'active' : '' }}">承認待ち</a>
        <a href="{{ route('attendance.correction.list', ['tab' => 'approved']) }}" 
           class="{{ $tab === 'approved' ? 'active' : '' }}">承認済み</a>
    </div>

    <table class="correction-table">
        <thead>
            <tr>
                <th>状態</th><th>名前</th><th>対象日時</th><th>申請理由</th><th>申請日時</th><th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $request)
            <tr>
                <td>
                    @if($request->status === 'pending') <span style="color: #e67e22; font-weight: bold;">承認待ち</span>
                    @else <span style="color: #27ae60; font-weight: bold;">承認済み</span>
                    @endif
                </td>
                <td>{{ $request->user->name }}</td>
                <td>{{ $request->target_date }}</td>
                <td>{{ $request->reason }}</td>
                <td>{{ $request->created_at->format('Y/m/d') }}</td>
                <td><a href="{{ route('attendance.detail', $request->attendance_id) }}">詳細</a></td>
            </tr>
            @empty
            <tr><td colspan="6">申請データはありません。</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection