<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;

class AttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 出勤時間が退勤時間より後だとバリデーションエラーになること()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        // 不正なデータを送る（退勤時間が開始時間より早い）
        $response = $this->from(route('attendance.detail', $attendance->id))
                         ->post(route('attendance.update', $attendance->id), [
                             'start_time' => '18:00',
                             'end_time'   => '09:00',
                             'remarks'    => 'テスト修正',
                         ]);

        // バリデーションエラーが返ることを確認
        $response->assertSessionHasErrors(['end_time']);
        $response->assertRedirect(route('attendance.detail', $attendance->id));
    }

  /** @test */
public function 備考が未入力だとバリデーションエラーになること()
{
    $user = User::factory()->create();
    $attendance = Attendance::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    // リクエストを送る際、reasonを空にする
    $response = $this->from(route('attendance.detail', $attendance->id))
                     ->post(route('attendance.update', $attendance->id), [
                         'start_time' => '09:00',
                         'end_time'   => '18:00',
                         'reason'     => '', // ここを remarks から reason に変更
                     ]);

    // assertSessionHasErrors で確認するキーも 'reason' に変更
    $response->assertSessionHasErrors(['reason']);
}
}