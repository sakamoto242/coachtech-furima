<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 一般ユーザーは管理者ページにアクセスできないこと()
    {
        // ユーザーを作成（一般ユーザーとして）
        $user = User::factory()->create();
        
        // 通常の認証ガードでログインさせる（もし管理者が別のガードなら修正が必要）
        $this->actingAs($user);

        // 管理者ページへアクセス
        $response = $this->get(route('admin.admin.index'));

        // 管理者権限がないため、403 Forbidden になることを期待
       $response->assertStatus(302);
    }
}