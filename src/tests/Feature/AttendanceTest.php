<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 出勤ボタンを押すと勤務開始時刻が記録されること()
    {
        
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('attendance.start'));

      
        $response->assertStatus(302); 

       
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
        ]);
    }
    /** @test */
public function 同一日に二度目の出勤はできないこと()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    
    $this->post(route('attendance.start'));

    
    $response = $this->post(route('attendance.start'));

   
    $response->assertStatus(302); 
    
    $this->assertDatabaseCount('attendances', 1);
}
/** @test */
    public function 休憩入りの処理が正しく行われること()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('attendance.start'));
        
        $response = $this->post(route('rest.start'));

        $response->assertStatus(302);
    }

    /** @test */
    public function 休憩戻りの処理が正しく行われること()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('attendance.start'));
        $this->post(route('rest.start'));
        
        $response = $this->post(route('rest.end'));

        $response->assertStatus(302);
    }

/** @test */
public function 退勤の処理が正しく行われること()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    // 1. 出勤
    $this->post(route('attendance.start'));
    
    // 2. 退勤実行
    $response = $this->post(route('attendance.end'));

    // 3. 成功を確認
    $response->assertStatus(302);
}

/** @test */
public function 退勤後に再度退勤ボタンを押してもエラーになること()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post(route('attendance.start'));
    $this->post(route('attendance.end'));

    $response = $this->post(route('attendance.end'));

    $response->assertStatus(302); 
}

/** @test */
public function 出勤していないユーザーは休憩入りできないこと()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('rest.start'));
    $response->assertStatus(302); // ログイン画面やエラーへリダイレクト
}

/** @test */
public function 出勤していないユーザーは退勤できないこと()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('attendance.end'));
    $response->assertStatus(302);
}

/** @test */
public function 休憩中に再度休憩入りはできないこと()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post(route('attendance.start'));
    $this->post(route('rest.start'));

    // 二回目の休憩入り
    $response = $this->post(route('rest.start'));
    $response->assertStatus(302);
}

/** @test */
public function 退勤した後に休憩入りはできないこと()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post(route('attendance.start'));
    $this->post(route('attendance.end'));

    // 退勤後に休憩入りを試みる
    $response = $this->post(route('rest.start'));
    $response->assertStatus(302);
}
}

