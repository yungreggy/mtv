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
        Schema::create('bumpers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique(); // Nom du bumper, unique
            $table->integer('year')->nullable(); // Année de création du bumper
            $table->time('duration')->nullable(); // Durée du bumper
            $table->string('thumbnail_image', 255)->nullable(); // Chemin vers l'image miniature
            $table->string('file_path', 255)->nullable(); // Chemin vers le fichier vidéo du bumper
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bumpers');
    }
};
