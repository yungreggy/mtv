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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('thumbnail_image', 255)->nullable();
            $table->text('biography')->nullable();
            $table->string('website', 255)->nullable();
            $table->string('main_genre', 100)->nullable();
            $table->integer('career_start_year')->nullable();
            $table->string('country_of_origin', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
