<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToMusicVideosTable extends Migration
{
    public function up()
    {
        Schema::table('music_videos', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('music_videos', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
}
