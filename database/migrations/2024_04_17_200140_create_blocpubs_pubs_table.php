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
        Schema::create('blocpubs_pubs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blocpub_id');
            $table->unsignedBigInteger('pub_id');
            $table->timestamps();

            // Définir les clés étrangères et les contraintes de suppression
            $table->foreign('blocpub_id')->references('id')->on('bloc_pubs')->onDelete('cascade');
            $table->foreign('pub_id')->references('id')->on('pubs')->onDelete('cascade');

            // Assurer l'unicité des paires blocpub-pub pour éviter les doublons
            $table->unique(['blocpub_id', 'pub_id'], 'blocpub_pub_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocpubs_pubs');
    }
};
