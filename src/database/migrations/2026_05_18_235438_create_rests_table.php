<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestsTable extends Migration
{
    public function up()
    {
        Schema::create('rests', function (Blueprint $table) {
            $table->id();
            // どの勤務（attendances）に対する休憩か
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade');
            // 休憩開始時間
            $table->time('start_time')->nullable();
            // 休憩終了時間
            $table->time('end_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rests');
    }
}