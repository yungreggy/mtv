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
        Schema::create('bloc_pubs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->time('duration')->nullable();
            $table->datetime('scheduled_start_time')->nullable();
            $table->datetime('scheduled_end_time')->nullable();
            $table->string('status', 100)->default('active'); // Suppose active, inactive, etc.
            $table->integer('priority')->default(0); // Default priority set to 0
            $table->text('description')->nullable();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->timestamps();

            // Foreign key relation
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('set null');

            // Unique constraint to ensure each name is unique per program
            $table->unique(['name', 'program_id'], 'bloc_pubs_name_program_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloc_pubs');
    }
};
