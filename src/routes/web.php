<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| メール認証
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('success', 'メール認証が完了しました！');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| 一般ユーザー（スタッフ）用
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/start', [AttendanceController::class, 'startWork'])->name('attendance.start');
    Route::post('/attendance/end', [AttendanceController::class, 'endWork'])->name('attendance.end');
    Route::post('/rest/start', [AttendanceController::class, 'startRest'])->name('rest.start');
    Route::post('/rest/end', [AttendanceController::class, 'endRest'])->name('rest.end');
    
    Route::get('/attendance/list', [AttendanceController::class, 'showList'])->name('attendance.list');
    Route::get('/attendance/my_correction_list', [AttendanceController::class, 'showMyCorrectionList'])->name('attendance.correction.list');
    Route::get('/attendance/report', [AttendanceController::class, 'showReport'])->name('attendance.report');
    
    Route::get('/attendance/{id}', [AttendanceController::class, 'showDetail'])->name('attendance.detail');
    Route::get('/attendance/correction/{id}', [AttendanceController::class, 'showCorrectionDetail'])->name('attendance.correction.detail');
    Route::post('/attendance/{id}/correction', [AttendanceController::class, 'submitCorrection'])->name('attendance.correction');
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'editDetail'])->name('attendance.edit');
    Route::post('/attendance/{id}/update', [AttendanceController::class, 'updateDetail'])->name('attendance.update');
});

/*
|--------------------------------------------------------------------------
| 管理者用
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminController::class, 'login']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/staff', [AdminController::class, 'showStaffList'])->name('staff.list');
        Route::get('/user/{id}', [AdminController::class, 'userAttendance'])->name('user.attendance');
        Route::get('/attendance/csv', [AdminController::class, 'exportCsv'])->name('attendance.csv');
        Route::get('/user/{id}/attendance/csv', [AdminController::class, 'exportCsv'])->name('user.attendance.csv');
        Route::get('/stamp_correction_request/list', [AdminController::class, 'showStampCorrectionList'])->name('stamp.correction.list');
        
        Route::get('/attendance/{id}', [AdminController::class, 'attendanceDetail'])->name('attendance.detail');
        Route::post('/attendance/{id}/update', [AdminController::class, 'updateAttendance'])->name('attendance.update');
        
        Route::get('/attendance/approve/{id}', [AdminController::class, 'showApproveDetail'])->name('attendance.approve.detail');
        Route::post('/attendance/{id}/approve', [AdminController::class, 'approveChange'])->name('approve');
        
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    });
});
