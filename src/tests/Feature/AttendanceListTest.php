<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;

class AttendanceListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 勤怠一覧画面に現在の月が表示されていること()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $currentMonth = now()->format('Y-m');

        // attendance.index を attendance.list に変更
        $response = $this->get(route('attendance.list'));

        $response->assertStatus(200);
        $response->assertSee($currentMonth);
    }

    /** @test */
    public function 前月ボタン押下時に前月の勤怠情報が表示されること()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $prevMonth = now()->subMonth()->format('Y-m');

        // attendance.index を attendance.list に変更
        $response = $this->get(route('attendance.list', ['month' => $prevMonth]));

        $response->assertStatus(200);
        $response->assertSee($prevMonth);
    }
}