<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampCorrectionRequest extends Model
{
    use HasFactory;

    // 一括登録（Mass Assignment）を許可するカラムを指定
   protected $fillable = [
        'user_id',
        'attendance_id',
        'original_start_time',
        'original_end_time',
        'requested_start_time',
        'requested_end_time',
        'requested_rest_start_1',
        'requested_rest_end_1',
        'requested_rest_start_2',
        'requested_rest_end_2',
        'reason',
        'status',
    ];

    /**
     * この申請を出したスタッフ（User）を取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * この申請が紐づいている元の勤怠データ（Attendance）を取得
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
