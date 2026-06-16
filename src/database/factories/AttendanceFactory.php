<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
{
    return [
        'user_id' => \App\Models\User::factory(),
        'date' => now()->format('Y-m-d'),
        'start_time' => '09:00:00', // 仮の開始時間
        'end_time' => '18:00:00',   // 仮の終了時間
    ];
}
}
