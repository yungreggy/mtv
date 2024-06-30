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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->integer('year');
            $table->unsignedBigInteger('director_id')->nullable();
            $table->unsignedBigInteger('label_id')->nullable();
            $table->text('description')->nullable();
            $table->time('duration')->nullable();
            $table->string('file_path', 255)->nullable();
            $table->string('local_image_path', 255)->nullable();
            $table->string('url_poster', 255)->nullable();
            $table->string('rating', 10)->nullable();
            $table->string('primary_language', 50)->nullable();
            $table->string('country_of_origin', 100)->nullable();
            $table->timestamps();

            $table->foreign('director_id')->references('id')->on('directors')->onDelete('set null');
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('set null');

            $table->unique(['title', 'year', 'director_id'], 'film_unique_constraint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
