<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebinarEventSessionTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar_event_session_times', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('webinar_event_id');
            $table->dateTime('session_start_date_time');
            $table->dateTime('session_end_date_time');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('webinar_event_id')->references('id')->on('webinar_events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webinar_event_session_times');
    }
}
