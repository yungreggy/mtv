<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleMusicVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_musicvideos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_schedule_id')->constrained('program_schedules')->onDelete('cascade');
            $table->foreignId('music_video_id')->constrained('music_videos')->onDelete('cascade');
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
        Schema::dropIfExists('schedule_musicvideos');
    }
}

