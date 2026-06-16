<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // 外部キー：usersテーブルのidと紐付け（これがないとエラーになります！）
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');       // 出勤日 (YYYY-MM-DD)
            $table->time('start_time'); // 出勤時刻
            $table->time('end_time')->nullable(); // 退勤時刻（最初は空なのでnullable）
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}