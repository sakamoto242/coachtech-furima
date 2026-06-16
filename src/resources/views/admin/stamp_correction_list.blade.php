@extends('layouts.app')

@section('content')
<style>
    .container { width: 85%; max-width: 1000px; margin: 40px auto; }
    
    /* 見出しエリア */
    .page-title { font-size: 24px; font-weight: bold; margin-bottom: 30px; display: flex; align-items: center; }
    .page-title::before { content: ""; display: block; width: 6px; height: 28px; background: #000; margin-right: 15px; }

    /* タブのスタイル */
    .tabs { display: flex; gap: 40px; margin-bottom: 20px; border-bottom: 1px solid #ddd; }
    .tab-link { text-decoration: none; color: #aaa; font-weight: bold; padding-bottom: 10px; transition: 0.3s; }
    .tab-link.active { color: #000; border-bottom: 2px solid #000; }

    /* テーブルのスタイル */
    .attendance-table { width: 100%; border-collapse: collapse; background: #fff; }
    .attendance-table th { color: #999; font-weight: normal; padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
    .attendance-table td { padding: 15px; border-bottom: 1px solid #eee; color: #333; }
    
    /* 状態表示のラベル */
    .status-pending { color: #d9534f; font-weight: bold; }
    .status-approved { color: #5cb85c; font-weight: bold; }
    .detail-link { color: #000; text-decoration: underline; font-weight: bold; }
</style>

<div class="container">
    <div class="page-title">申請一覧</div>

    <div class="tabs">
        <a href="?tab=pending" class="tab-link {{ $tab === 'pending' ? 'active' : '' }}">承認待ち</a>
        <a href="?tab=approved" class="tab-link {{ $tab === 'approved' ? 'active' : '' }}">承認済み</a>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $req)
            <tr>
                <td>
                    <span class="{{ $req->status === 'approved' ? 'status-approved' : 'status-pending' }}">
                        {{ $req->status === 'approved' ? '承認済み' : '承認待ち' }}
                    </span>
                </td>
                <td>{{ $req->user->name ?? '不明' }}</td>
                <td>{{ \Carbon\Carbon::parse($req->attendance->date ?? '')->format('Y/m/d') }}</td>
                <td>{{ Str::limit($req->reason, 20) }}</td>
                <td>{{ $req->created_at->format('Y/m/d') }}</td>
               <td>
    <a href="{{ route('admin.attendance.approve.detail', ['id' => $req->id]) }}">詳細</a>
</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #999;">該当する申請はありません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection