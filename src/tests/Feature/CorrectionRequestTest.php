<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\StampCorrectionRequest;

class CorrectionRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ユーザーが勤怠修正申請を送信できること()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        // 送信データ名をコントローラーに合わせて修正
        $response = $this->post(route('attendance.correction', ['id' => $attendance->id]), [
            'start_time' => '10:00:00', // 修正
            'end_time'   => '19:00:00', // 修正
            'reason'     => 'テストの理由',
        ]);

        $response->assertStatus(302);

        // DBの検証は、テーブル内のカラム名（requested_start_time）に対して行う
        $this->assertDatabaseHas('stamp_correction_requests', [
            'attendance_id'        => $attendance->id,
            'status'               => 'pending',
            'requested_start_time' => '10:00:00',
            'requested_end_time'   => '19:00:00',
        ]);
    }
}