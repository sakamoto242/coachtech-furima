<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRestColumnsToStampCorrectionRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stamp_correction_requests', function (Blueprint $table) {
            $table->time('requested_rest_start_1')->nullable();
            $table->time('requested_rest_end_1')->nullable();
            $table->time('requested_rest_start_2')->nullable();
            $table->time('requested_rest_end_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stamp_correction_requests', function (Blueprint $table) {
            $table->dropColumn([
                'requested_rest_start_1', 
                'requested_rest_end_1', 
                'requested_rest_start_2', 
                'requested_rest_end_2'
            ]);
        });
    }
}