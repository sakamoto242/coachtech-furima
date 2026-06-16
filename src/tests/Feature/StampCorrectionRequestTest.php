<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;

class StampCorrectionRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 勤怠修正申請が送信できること()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // 申請対象の勤怠データを作成
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        $payload = [
            'attendance_id' => $attendance->id,
            'date' => $attendance->date,
            'start_time' => '09:00',
            'end_time' => '18:00',
            'reason' => '修正理由のテストです'
        ];

        // 申請送信リクエスト
        $response = $this->post(route('attendance.correction', ['id' => $attendance->id]), $payload);

        // 申請テーブルにデータが保存されているか確認
        $this->assertDatabaseHas('stamp_correction_requests', [
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'reason' => '修正理由のテストです'
        ]);

        // 完了後に一覧へリダイレクトされるか確認
        $response->assertRedirect(route('attendance.correction.list'));
    }
}