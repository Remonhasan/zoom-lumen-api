<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebinarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('organogram_id')->nullable();

            $table->unsignedBigInteger('organization_id');
            $table->string('event_name', 255);
            $table->longText('event_description');
            $table->string('event_url', 255);
            $table->unsignedBigInteger('live_meeting_account_id');
            $table->dateTime('publish_date_time');
            $table->dateTime('apply_last_date_time');
            $table->string('supporting_documents')->nullable();
            $table->longText('speech_to_text_content');
            $table->longText('event_gist');
            $table->longText('details')->nullable();
            $table->string('status', 64);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->integer('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webinar_events');
    }
}
