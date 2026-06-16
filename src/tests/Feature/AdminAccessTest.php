<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 一般ユーザーは管理者ページにアクセスできないこと()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        // ルート名を修正
        $response = $this->get(route('admin.admin.index'));

        // 管理者ガードが効いていれば 302（ログイン画面へ）が返るはず
        $response->assertStatus(302); 
    }
    /** @test */
public function 管理者はスタッフ一覧ページにアクセスできること()
{
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin, 'admin'); 

    $response = $this->get(route('admin.staff.list'));

    $response->assertStatus(200);
}
/** @test */
public function 管理者はユーザーの勤怠詳細ページにアクセスできること()
{
    $admin = User::factory()->create(['is_admin' => true]);

    $targetUser = User::factory()->create(); 

    $this->actingAs($admin, 'admin');

    $response = $this->get(route('admin.user.attendance', ['id' => $targetUser->id]));

    $response->assertStatus(200);
}
/** @test */
public function 管理者が勤務修正を承認できること()
{
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();
    
    // 承認待ちデータを作成
    $correction = \App\Models\StampCorrectionRequest::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending' 
    ]);

    $this->actingAs($admin, 'admin');

    // 承認アクションを実行（'action' => 'approve' を追加）
    $response = $this->post(route('admin.approve', ['id' => $correction->id]), [
        'action' => 'approve'
    ]);

    // 成功してリダイレクトされること
    $response->assertStatus(302); 
    
    // DB上でステータスが「approved」に更新されたか確認
    $this->assertDatabaseHas('stamp_correction_requests', [
        'id' => $correction->id,
        'status' => 'approved'
    ]);
}
/** @test */
public function 管理者はCSVをダウンロードできること()
{
    $admin = User::factory()->create(['is_admin' => true]);
    $this->actingAs($admin, 'admin');

    // CSV出力ルートへアクセス
    $response = $this->get(route('admin.attendance.csv'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
}
}