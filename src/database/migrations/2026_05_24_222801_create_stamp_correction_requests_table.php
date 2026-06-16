<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stamp_correction_requests', function (Blueprint $blueprint) {
            $blueprint->id();
            // 誰の申請か（usersテーブルと紐付け、退職時にデータが消えないよう制限）
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade');
            // どの勤怠データの修正か（attendancesテーブルと紐付け）
            $blueprint->foreignId('attendance_id')->constrained()->onDelete('cascade');
            
            // 修正後の時間を保存するカラム（Figmaの仕様に合わせてセット）
            $blueprint->time('original_start_time')->nullable(); // 修正前の出勤時間（任意）
            $blueprint->time('original_end_time')->nullable();   // 修正前の退勤時間（任意）
            $blueprint->time('requested_start_time');           // 修正希望の出勤時間
            $blueprint->time('requested_end_time');             // 修正希望の退勤時間
            
            // 申請理由
            $blueprint->text('reason');
            
            // 承認ステータス（pending: 承認待ち, approved: 承認済み, rejected: 却下）
            $blueprint->string('status')->default('pending');
            
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stamp_correction_requests');
    }
};