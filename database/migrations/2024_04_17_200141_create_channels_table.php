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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();  // Nom du canal, unique
            $table->text('description')->nullable();  // Description du canal
            $table->string('thumbnail_image', 255)->nullable();  // Chemin vers l'image miniature du canal
            $table->string('logo', 255)->nullable();  // Chemin vers le logo du canal
            $table->timestamps();  // Timestamps pour created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
