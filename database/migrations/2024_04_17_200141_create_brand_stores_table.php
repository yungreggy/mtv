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
        Schema::create('brand_stores', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();  // Nom unique pour chaque marque ou magasin
            $table->text('description')->nullable();  // Description de la marque ou du magasin
            $table->string('logo_image', 255)->nullable();  // Chemin vers le logo de la marque ou du magasin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_stores');
    }
};

