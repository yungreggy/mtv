<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTvShowID extends Migration
{
    public function up()
    {
        Schema::create('tv_shows_episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('tv_shows_seasons')->onDelete('cascade');
            $table->string('title');
            $table->date('air_date')->nullable();
            $table->text('description')->nullable();
            $table->integer('duration')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tv_shows_episodes');
    }
}
