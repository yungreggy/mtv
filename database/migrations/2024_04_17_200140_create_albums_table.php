<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->integer('year');
            $table->unsignedBigInteger('label_id')->nullable();
            $table->unsignedBigInteger('artist_id')->nullable();
            $table->string('thumbnail_image', 255)->nullable();
            $table->text('description')->nullable();
            $table->integer('track_count')->nullable();
            $table->date('release_date')->nullable();
            $table->string('url', 255)->nullable();

            $table->timestamps();

            $table->foreign('label_id')->references('id')->on('labels')->onDelete('set null');
            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('set null');

            $table->unique(['title', 'artist_id', 'year'], 'album_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
