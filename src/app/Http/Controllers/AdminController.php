<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Rest;
use App\Models\StampCorrectionRequest;
use Carbon\Carbon;

class AdminController extends Controller
{
    // --- ログイン・ログアウト ---
    public function showLogin() {
        if (Auth::guard('admin')->check()) return redirect()->route('admin.dashboard');
        return view('admin.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required']]);
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors(['email' => 'ログイン情報が正しくありません。'])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'ログアウトしました');
    }

    // --- ダッシュボード ---
    public function dashboard(Request $request)
    {
        $targetDate = $request->query('date', Carbon::today()->format('Y-m-d'));
        $attendances = Attendance::with('user')->where('date', $targetDate)->get();
        return view('admin.dashboard', compact('attendances', 'targetDate'));
    }

    // --- 勤怠詳細画面表示 ---
    // 修正詳細画面
public function attendanceDetail($id) {
    $attendance = \App\Models\Attendance::with('rests')->findOrFail($id);
    $isApprovalView = false; // 通常の修正画面
    return view('admin.attendance_detail', compact('attendance', 'isApprovalView'));
}

// 承認用画面（もし申請一覧から遷移する場合）
public function approveDetail($id) {
    $attendance = \App\Models\Attendance::with('rests')->findOrFail($id);
    $isApprovalView = true; // 承認画面
    return view('admin.attendance_detail', compact('attendance', 'isApprovalView'));
}

  // --- 勤怠修正の保存処理 ---
    public function updateAttendance(Request $request, $id)
    {
        // 1. 入力値のバリデーション
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'remarks'    => 'required',
            'rests.*.start_time' => 'nullable|date_format:H:i',
            'rests.*.end_time'   => 'nullable|date_format:H:i',
        ]);

        $attendance = Attendance::findOrFail($id);
        
        // 2. ★ここが重要：Carbon::now() ではなく、フォームから送られた値を使用する
        $attendance->update([
            'start_time' => $request->start_time . ':00',
            'end_time'   => $request->end_time . ':00',
            'remarks'    => $request->remarks,
        ]);

        // 3. 休憩時間の更新（既存のロジックを維持）
        if ($request->has('rests')) {
            foreach ($request->rests as $index => $restData) {
                if (!empty($restData['start_time']) && !empty($restData['end_time'])) {
                    // 休憩レコードの特定（インデックスで取得、またはIDで取得）
                    $rest = $attendance->rests->get($index);
                    
                    if ($rest) {
                        $rest->update([
                            'start_time' => $restData['start_time'] . ':00',
                            'end_time'   => $restData['end_time'] . ':00',
                        ]);
                    } else {
                        $attendance->rests()->create([
                            'start_time' => $restData['start_time'] . ':00',
                            'end_time'   => $restData['end_time'] . ':00',
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.dashboard', ['date' => $attendance->date])
                         ->with('success', '勤怠情報を修正しました');
    }

    // --- 承認・却下処理 ---
   public function approveChange(Request $request, $id) 
{
    // 申請データと、紐付いている勤怠・休憩情報を一括で取得
    $stampCorrectionRequest = StampCorrectionRequest::with(['attendance.rests'])->findOrFail($id);
    
    if ($request->input('action') === 'approve') {
        $stampCorrectionRequest->status = 'approved';
        $stampCorrectionRequest->save();
        
        $attendance = $stampCorrectionRequest->attendance;
        if ($attendance) {
            // 1. 出退勤の更新
            $attendance->update([
                'start_time' => $stampCorrectionRequest->requested_start_time,
                'end_time'   => $stampCorrectionRequest->requested_end_time,
            ]);

            // 2. 休憩時間の更新（申請データに休憩情報がある場合）
            if ($stampCorrectionRequest->requested_rest_start_time) {
                // 既存の休憩レコードがある場合は更新、なければ作成
                $rest = $attendance->rests->first() ?? new Rest(['attendance_id' => $attendance->id]);
                $rest->update([
                    'start_time' => $stampCorrectionRequest->requested_rest_start_time,
                    'end_time'   => $stampCorrectionRequest->requested_rest_end_time,
                ]);
            }
        }
    } else {
        $stampCorrectionRequest->status = 'rejected';
        $stampCorrectionRequest->save();
    }
    
 // 修正後
return redirect()->route('admin.stamp.correction.list', ['tab' => 'approved'])
                 ->with('success', '申請を承認しました');
}
    // --- 修正申請一覧 ---
    public function showStampCorrectionList(Request $request) {
        $tab = $request->query('tab', 'pending');
        $status = ($tab === 'approved') ? 'approved' : 'pending';
        $requests = StampCorrectionRequest::with('user')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.stamp_correction_list', compact('requests', 'tab'));
    }

    // --- CSV出力 ---
    public function exportCsv(Request $request, $id = null)
    {
        $query = Attendance::with(['user', 'rests']);
        if ($id) {
            $targetMonth = $request->query('month', Carbon::now()->format('Y-m'));
            $query->where('user_id', $id)->where('date', 'like', $targetMonth . '%')->orderBy('date', 'asc');
            $fileName = 'staff_attendance_' . $id . '_' . $targetMonth . '.csv';
        } else {
            $targetDate = $request->query('date', Carbon::today()->format('Y-m-d'));
            $query->where('date', $targetDate);
            $fileName = 'attendance_' . $targetDate . '.csv';
        }

        $attendances = $query->get();
        $headers = ['Content-Type' => 'text/csv; charset=UTF-8', 'Content-Disposition' => 'attachment; filename=' . $fileName];
        return response()->stream(function () use ($attendances, $id) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $id ? ['日付', '勤務開始', '勤務終了', '休憩時間', '勤務時間'] : ['スタッフ名', '出勤時間', '退勤時間', '休憩時間', '勤務時間']);
            foreach ($attendances as $a) {
                fputcsv($file, [$a->date ?? $a->user->name, $a->start_time, $a->end_time, '0:00', '8:00']);
            }
            fclose($file);
        }, 200, $headers);
    }

   // --- ユーザー勤怠一覧 ---
    public function userAttendance(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $targetMonth = $request->query('month', \Carbon\Carbon::now()->format('Y-m'));
        
        $currentDate = \Carbon\Carbon::parse($targetMonth . '-01');
        $prevMonth = $currentDate->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentDate->copy()->addMonth()->format('Y-m');
        
        // その月の全日付リストを作成
        $daysInMonth = $currentDate->daysInMonth;
        $allDates = [];
        for ($i = 0; $i < $daysInMonth; $i++) {
            $allDates[] = $currentDate->copy()->addDays($i)->format('Y-m-d');
        }

        // データを取得し、日付をキーにして配列化
        $attendances = \App\Models\Attendance::with('rests')
            ->where('user_id', $user->id)
            ->where('date', 'like', $targetMonth . '%')
            ->get()
            ->keyBy('date');

        // 全日付とデータを紐付ける
        $attendanceList = [];
        foreach ($allDates as $date) {
            $attendanceList[$date] = $attendances->get($date);
        }
            
        return view('admin.user_attendance', compact('user', 'attendanceList', 'targetMonth', 'prevMonth', 'nextMonth'));
    }

    // 申請一覧からの遷移用（承認画面）
public function showApproveDetail($id) {
    // 1. 申請情報($idは申請のID)をまず取得
   $correctionRequest = \App\Models\StampCorrectionRequest::with(['user', 'attendance.rests'])->findOrFail($id);
    
    // 2. 申請に紐付いている「勤怠データ」を特定する
    $attendance = $correctionRequest->attendance;
    
    $isApprovalView = true;
    
    // 3. 正しい勤怠データと申請データを渡す
    return view('admin.attendance_detail', compact('attendance', 'isApprovalView', 'correctionRequest'));
}
// スタッフ一覧表示用メソッド
public function showStaffList()
{
    // ユーザーデータを取得
    $users = \App\Models\User::all();
    
    // ビューにデータを渡す（compact('users') が必要です）
    return view('admin.staff_list', compact('users'));
}
}