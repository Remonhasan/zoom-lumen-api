<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebinarEventReporterDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar_event_reporter_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('webinar_event_id');
            $table->string('reporter_name', 255);
            $table->string('reporter_email', 255);
            $table->string('reporter_phone', 60);
            $table->integer('reporter_order')->nullable();
            $table->dateTime('reporter_work_deadline')->nullable();

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
        Schema::dropIfExists('webinar_event_reporter_details');
    }
}
