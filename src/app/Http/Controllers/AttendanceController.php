<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Rest;
use Carbon\Carbon;
use App\Http\Requests\UpdateAttendanceRequest;

class AttendanceController extends Controller
{
    /**
     * 打刻ページの表示 (US004)
     */
   public function index()
{
    $user = Auth::user();
    $today = Carbon::today()->format('Y-m-d');

    // 勤怠データを取得（休憩リレーションも念のため取得）
    $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', $today)
                            ->first();

        // 状態を判定するためのフラグ
        $canStartWork = false;
        $canEndWork = false;
        $canStartRest = false;
        $canEndRest = false;

        if (!$attendance) {
            $canStartWork = true;
        } elseif ($attendance->end_time) {
            // 退勤済み
        } elseif ($latestRest = Rest::where('attendance_id', $attendance->id)->whereNull('end_time')->latest()->first()) {
            $canEndRest = true;
        } else {
            $canEndWork = true;
            $canStartRest = true;
        }

        // ステータスをビューへ渡すためのフラグを作成
        // 'none': 勤務外, 'attendance': 勤務中, 'rest': 休憩中
        $status = 'none';
        if ($canEndRest) {
            $status = 'rest';
        } elseif ($canEndWork || $canStartRest) {
            $status = 'attendance';
        }

       return view('attendance.index', compact(
        'canStartWork', 'canEndWork', 'canStartRest', 'canEndRest', 'status', 'attendance'
    ));
    }
    /**
     * 勤務開始 (US005)
     */
    public function startWork()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // 当日の出勤データがすでにないか念のためチェック
        $exists = Attendance::where('user_id', $user->id)
                            ->where('date', $now->format('Y-m-d'))
                            ->exists();

        if (!$exists) {
            // 勤怠レコードを新規作成
            Attendance::create([
                'user_id' => $user->id,
                'date' => $now->format('Y-m-d'),
                'start_time' => $now->format('H:i:s'),
            ]);
        }

        return redirect('/')->with('success', '出勤しました');
    }

    /**
     * 勤務終了 (US006)
     */
    public function endWork()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // 本日の未退勤の出勤データを取得
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('date', $now->format('Y-m-d'))
                                ->whereNull('end_time')
                                ->first();

        if ($attendance) {
            // 退勤時刻を更新
            $attendance->update([
                'end_time' => $now->format('H:i:s'),
            ]);
        }

        return redirect('/')->with('success', '退勤しました');
    }

    /**
     * 休憩開始 (US007)
     */
    public function startRest()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // 本日の出勤データを取得
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('date', $now->format('Y-m-d'))
                                ->first();

        if ($attendance) {
            // 休憩レコードを新規作成
            Rest::create([
                'attendance_id' => $attendance->id,
                'start_time' => $now->format('H:i:s'),
            ]);
        }

        return redirect('/')->with('success', '休憩を開始しました');
    }

    /**
     * 休憩終了 (US008)
     */
    public function endRest()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // 本日の出勤データを取得
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('date', $now->format('Y-m-d'))
                                ->first();

        if ($attendance) {
            // 最後の未終了の休憩データを取得
            $latestRest = Rest::where('attendance_id', $attendance->id)
                              ->whereNull('end_time')
                              ->latest()
                              ->first();

            if ($latestRest) {
                // 休憩終了時刻を更新
                $latestRest->update([
                    'end_time' => $now->format('H:i:s'),
                ]);
            }
        }

        return redirect('/')->with('success', '休憩を終了しました');
    }
    /**
     * 勤怠一覧ページの表示 (US009)
     */
   public function showList(Request $request)
{
    $targetMonth = $request->query('month', Carbon::now()->format('Y-m'));
    
    // 前月・次月の計算
    $carbonMonth = Carbon::parse($targetMonth . '-01');
    $prevMonth = $carbonMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $carbonMonth->copy()->addMonth()->format('Y-m');

    // 1. その月の全日付を生成
    $daysInMonth = $carbonMonth->daysInMonth;
    $allDates = [];
    for ($i = 0; $i < $daysInMonth; $i++) {
        $allDates[] = $carbonMonth->copy()->addDays($i)->format('Y-m-d');
    }

    // 2. データベースからその月の勤怠データを取得して配列化（日付をキーにする）
    $attendances = Attendance::with('rests')
        ->where('user_id', Auth::id())
        ->where('date', 'like', $targetMonth . '%')
        ->get()
        ->keyBy('date');

    // 3. 全日付に対して勤怠データがあればセット、なければnull
    $attendanceList = [];
    foreach ($allDates as $date) {
        $attendanceList[$date] = $attendances->get($date);
    }

    return view('attendance.list', compact('attendanceList', 'targetMonth', 'prevMonth', 'nextMonth'));
}
    /**
 * スタッフ用：勤怠詳細画面の表示
 */
public function showDetail($id)
{
    $attendance = \App\Models\Attendance::findOrFail($id);

    // 「承認待ち(pending)」の申請が既に存在するかチェック
    $activeRequest = \App\Models\StampCorrectionRequest::where('attendance_id', $id)
        ->where('status', 'pending')
        ->first();

    return view('attendance.detail', compact('attendance', 'activeRequest'));
}

/**
     * スタッフ用：勤怠編集画面の表示
     */
    public function editDetail($id)
    {
        $attendance = \App\Models\Attendance::where('user_id', auth()->id())->findOrFail($id);
        
        // 承認待ちの申請があるか確認して取得
        $activeRequest = \App\Models\StampCorrectionRequest::where('attendance_id', $id)
            ->where('status', 'pending')
            ->first();

        return view('attendance.edit', compact('attendance', 'activeRequest'));
    }

    /**
     * スタッフ用：勤怠データの更新（修正申請として送信）
     */
    public function updateDetail(Request $request, $id)
    {
        // 休憩データもバリデーション対象に含める
        $request->validate([
            'start_time'   => 'required',
            'end_time'     => 'required|after:start_time',
            'rest_start_1' => 'nullable',
            'rest_end_1'   => 'nullable|after:rest_start_1',
            'rest_start_2' => 'nullable',
            'rest_end_2'   => 'nullable|after:rest_start_2',
            'reason'       => 'required',
        ]);

        $attendance = \App\Models\Attendance::where('user_id', auth()->id())->findOrFail($id);
        
        \App\Models\StampCorrectionRequest::create([
            'user_id'              => auth()->id(),
            'attendance_id'        => $attendance->id,
            'original_start_time'  => $attendance->start_time,
            'original_end_time'    => $attendance->end_time,
            'requested_start_time' => $request->input('start_time'),
            'requested_end_time'   => $request->input('end_time'),
            'requested_rest_start_1' => $request->input('rest_start_1'),
            'requested_rest_end_1'   => $request->input('rest_end_1'),
            'requested_rest_start_2' => $request->input('rest_start_2'),
            'requested_rest_end_2'   => $request->input('rest_end_2'),
            'reason'               => $request->input('reason'),
            'status'               => 'pending',
        ]);

        return redirect()->route('attendance.correction.list')->with('success', '勤怠修正申請を提出しました。');
    }
    /**
     * スタッフ用：修正申請の送信処理
     */
    public function submitCorrection(UpdateAttendanceRequest $request, $id)
    {
        // 1. バリデーション
        $request->validate([
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
            'reason'     => 'required',
        ], [
            'end_time.after' => '退勤時間は出勤時間より後に設定してください',
            'reason.required' => '備考を記入してください',
        ]);

        $attendance = \App\Models\Attendance::where('user_id', auth()->id())->findOrFail($id);

        // 2. 二重申請チェック
        $exists = \App\Models\StampCorrectionRequest::where('attendance_id', $id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', '既にこの日時の申請が提出されています。');
        }

        // 3. 修正申請テーブルに保存（ここを確実に実行する）
        \App\Models\StampCorrectionRequest::create([
            'user_id'              => auth()->id(),
            'attendance_id'        => $attendance->id,
            'original_start_time'  => $attendance->start_time,
            'original_end_time'    => $attendance->end_time,
            'requested_start_time' => $request->input('start_time'),
            'requested_end_time'   => $request->input('end_time'),
            'reason'               => $request->input('reason'),
            'status'               => 'pending',
        ]);

        // 成功したら一覧画面へリダイレクト
        return redirect()->route('attendance.correction.list')->with('success', '勤怠修正申請を提出しました。');
    }
   /**
     * スタッフ用：修正申請一覧の表示
     */
    public function showMyCorrectionList(Request $request)
    {
        $tab = $request->query('tab', 'pending');
        $status = ($tab === 'approved') ? 'approved' : 'pending';

        // 1. with('attendance') で関連データを一括取得（N+1問題解消）
        // 2. ログインユーザーのデータのみ取得
        // 3. 申請のステータスで絞り込み
        $requests = \App\Models\StampCorrectionRequest::with('attendance')
            ->where('user_id', auth()->id())
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('attendance.my_correction_list', compact('requests', 'tab'));
    }
   /**
     * スタッフ用：マイ勤怠レポート画面の表示（完全修正版）
     */
   public function showReport(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $months = [];
        for ($i = 5; $i >= 0; $i--) { $months[] = $now->copy()->subMonths($i)->format('Y-m'); }

        $reportData = [];
        $grandTotalWorkSeconds = 0;
        $grandTotalOverSeconds = 0;
        $totalWorkDays = 0;

        // ★追加: ここで変数を初期化（これでUndefined variableエラーを防ぎます）
        $lateCount = 0;
        $earlyLeaveCount = 0;
        $overtimeDaysCount = 0;
        $currentMonthStr = $now->format('Y-m');

        foreach ($months as $m) {
            $attendances = Attendance::with('rests')
                ->where('user_id', $user->id)
                ->where('date', 'like', $m . '%')
                ->get();

            $monthWorkSeconds = 0;
            $monthOverSeconds = 0;

            foreach ($attendances as $attendance) {
                if ($attendance->start_time && $attendance->end_time) {
                    $totalWorkDays++;
                    $sec = $attendance->getTotalWorkSeconds();
                    $monthWorkSeconds += $sec;
                    if ($sec > (8 * 3600)) $monthOverSeconds += ($sec - (8 * 3600));

                    // 異常検知（当月のみ）
                    if ($m === $currentMonthStr) {
                        if (Carbon::parse($attendance->start_time)->gt(Carbon::parse('09:00:00'))) $lateCount++;
                        if (Carbon::parse($attendance->end_time)->lt(Carbon::parse('18:00:00'))) $earlyLeaveCount++;
                        if ($sec > (10 * 3600)) $overtimeDaysCount++;
                    }
                }
            }
            $grandTotalWorkSeconds += $monthWorkSeconds;
            $grandTotalOverSeconds += $monthOverSeconds;
            $reportData[] = [
                'month' => $m,
                'work_time' => sprintf('%dh %dm', floor($monthWorkSeconds / 3600), floor(($monthWorkSeconds % 3600) / 60)),
                'over_time' => sprintf('%dh %dm', floor($monthOverSeconds / 3600), floor(($monthOverSeconds % 3600) / 60)),
            ];
        }
        
        $totalWorkFormatted = sprintf('%dh %dm', floor($grandTotalWorkSeconds / 3600), floor(($grandTotalWorkSeconds % 3600) / 60));
        $totalOverFormatted = sprintf('%dh %dm', floor($grandTotalOverSeconds / 3600), floor(($grandTotalOverSeconds % 3600) / 60));
        $avgSec = $totalWorkDays > 0 ? ($grandTotalWorkSeconds / $totalWorkDays) : 0;
        $avgWorkFormatted = sprintf('%dh %dm', floor($avgSec / 3600), floor(($avgSec % 3600) / 60));

        return view('attendance.report', compact(
            'totalWorkFormatted', 'totalOverFormatted', 'avgWorkFormatted', 
            'reportData', 'lateCount', 'earlyLeaveCount', 'overtimeDaysCount'
        ));
    }
    /**
 * 修正申請詳細画面の表示
 */
public function showCorrectionDetail($id)
{
    // 申請データを取得
    $requestData = \App\Models\StampCorrectionRequest::with('attendance')
        ->where('id', $id)
        ->where('user_id', auth()->id()) // 自分の申請のみ閲覧可能
        ->firstOrFail();

    return view('attendance.correction_detail', compact('requestData'));
}

}