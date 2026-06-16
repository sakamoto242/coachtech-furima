<?php

namespace Database\Factories;

use App\Models\StampCorrectionRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class StampCorrectionRequestFactory extends Factory
{
    protected $model = StampCorrectionRequest::class;

   public function definition()
{
    return [
        'user_id' => \App\Models\User::factory(),
        'attendance_id' => \App\Models\Attendance::factory(),
        'status' => 'pending',
        'requested_start_time' => '09:00:00',
        'requested_end_time' => '18:00:00',
        'reason' => 'テストのための修正申請', // 追加
    ];
}
}