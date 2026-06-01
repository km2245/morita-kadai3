<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreakCorrectionRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('break_correction_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stamp_correction_request_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('breaktime_id')
                ->constrained()
                ->onDelete('cascade');

            $table->time('before_start_time');

            $table->time('after_start_time');

            $table->time('before_end_time');

            $table->time('after_end_time');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('break_correction_requests');
    }
}
