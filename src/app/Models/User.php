<?php

namespace App\Models;

// 1. MustVerifyEmail をインポートします
use Illuminate\Contracts\Auth\MustVerifyEmail; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 2. implements MustVerifyEmail を追加します
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * 複数代入可能な属性
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * シリアライズ時に隠す属性
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 勤怠モデルとのリレーション
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

public function isFinishedWork()
{
    // 今日が「退勤済み」かどうかを確認
    $attendance = $this->attendances()
        ->whereDate('date', today()) // 日付カラムが 'date' の場合
        ->first();

    // デバッグ用に現在の attendance の中身を確認
    // \Log::info($attendance); // これで laravel.log に詳細が出ます

    // 勤怠データが存在し、かつ退勤時間(end_time)が入力されている場合のみ true
    return !empty($attendance) && !empty($attendance->end_time);
}
}