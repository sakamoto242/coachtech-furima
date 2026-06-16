<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;

class CorrectionRequestValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 出勤時間が退勤時間より後の場合はエラーになること()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        // ルート名を 'attendance.correction' に修正
        $response = $this->post(route('attendance.correction', ['id' => $attendance->id]), [
            'start_time' => '18:00:00',
            'end_time'   => '09:00:00',
            'reason'     => '修正申請'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['start_time', 'end_time']);
    }

    /** @test */
    public function 備考が未入力の場合はエラーになること()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        // ルート名を 'attendance.correction' に修正
        $response = $this->post(route('attendance.correction', ['id' => $attendance->id]), [
            'start_time' => '09:00:00',
            'end_time'   => '18:00:00',
            'reason'     => '' 
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['reason']);
    }
}