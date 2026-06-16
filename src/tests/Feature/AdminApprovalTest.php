<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\StampCorrectionRequest;

class AdminApprovalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 管理者が勤怠修正申請を承認できること()
    {
        // 1. 準備: 管理者を作成してログイン
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        // 2. 準備: 勤怠データと申請データを作成
        $attendance = Attendance::factory()->create([
            'start_time' => '09:00:00',
            'end_time'   => '18:00:00'
        ]);
        
        $request = StampCorrectionRequest::factory()->create([
            'attendance_id' => $attendance->id,
            'status' => 'pending',
            'requested_start_time' => '10:00:00',
            'requested_end_time'   => '19:00:00',
        ]);

        // 3. 実行: 承認リクエスト（approveChangeメソッドを呼ぶルートを指定）
        // ※ルート名は routes/web.php で確認したものに合わせてください
        $response = $this->post(route('admin.approve', ['id' => $request->id]), [
    'action' => 'approve'
]);

        // 4. 検証: 申請ステータスが承認済みになっているか
        $this->assertDatabaseHas('stamp_correction_requests', [
            'id' => $request->id,
            'status' => 'approved'
        ]);

        // 5. 検証: 勤怠データが更新されているか
        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'start_time' => '10:00:00',
            'end_time'   => '19:00:00',
        ]);
        
        $response->assertRedirect();
    }
}