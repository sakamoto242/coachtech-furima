<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'date', 'start_time', 'end_time'];

    public function user() { return $this->belongsTo(User::class); }
    public function rests() { return $this->hasMany(Rest::class); }

    public function getTotalRestTimeAttribute()
    {
        $seconds = $this->rests->sum(function ($rest) {
            if (!$rest->start_time || !$rest->end_time) return 0;
            return Carbon::parse($rest->end_time)->diffInSeconds(Carbon::parse($rest->start_time));
        });
        return sprintf('%02d:%02d', floor($seconds / 3600), floor(($seconds % 3600) / 60));
    }

    public function getTotalWorkTimeAttribute()
    {
        if (!$this->start_time || !$this->end_time) return '0:00';
        
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        
        $workSeconds = $end->diffInSeconds($start);
        
        // 休憩秒数を引く
        $restSeconds = $this->rests->sum(function ($rest) {
            if (!$rest->start_time || !$rest->end_time) return 0;
            return Carbon::parse($rest->end_time)->diffInSeconds(Carbon::parse($rest->start_time));
        });

        $total = max(0, $workSeconds - $restSeconds);
        return sprintf('%02d:%02d', floor($total / 3600), floor(($total % 3600) / 60));
    }

    public function getTotalWorkSeconds()
    {
        // $this->date (例: 2026-06-15) と時刻を結合して正確な日時を作る
        $start = Carbon::parse($this->date . ' ' . $this->start_time);
        $end = Carbon::parse($this->date . ' ' . $this->end_time);

        $restSeconds = $this->rests->sum(function ($rest) {
            if (!$rest->start_time || !$rest->end_time) return 0;
            $rStart = Carbon::parse($this->date . ' ' . $rest->start_time);
            $rEnd = Carbon::parse($this->date . ' ' . $rest->end_time);
            return $rEnd->diffInSeconds($rStart);
        });

        return max(0, $end->diffInSeconds($start) - $restSeconds);
    }
}