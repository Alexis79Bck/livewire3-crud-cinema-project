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
        Schema::create('seats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('auditorium_id')->constrained('auditoriums')->onDelete('cascade');
            $table->string('row', 2); // A, B, C, etc.
            $table->integer('seat_number');
            $table->enum('type', ['standard', 'premium', 'vip', 'accessible']);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->unique(['auditorium_id', 'row', 'seat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
