<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('webinar_events', function (Blueprint $table) {
            if (!Schema::hasColumn('webinar_events', 'meeting_id', 'start_at', 'duration', 'password', 'start_url', 'join_url', 'topic')) {
                $table->string('meeting_id')->nullable()->after('details');
                $table->dateTime('start_at')->nullable()->after('meeting_id');
                $table->integer('duration')->nullable()->after('start_at');
                $table->string('password')->nullable()->after('duration');
                $table->text('start_url')->nullable()->after('password');
                $table->text('join_url')->nullable()->after('start_url');
                $table->string('topic')->nullable()->after('join_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('webinar_events', function (Blueprint $table) {
            //
        });
    }
};
