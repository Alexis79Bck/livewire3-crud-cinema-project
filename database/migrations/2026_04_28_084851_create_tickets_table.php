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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignUuid('seat_id')->constrained('seats')->restrictOnDelete();
            $table->foreignUuid('showtime_id')->constrained('showtimes')->restrictOnDelete();
            $table->string('ticket_status', 20)->default('ACTIVE');
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('qr_code', 255)->nullable();
            $table->string('checksum', 64)->nullable();
            $table->timestamps();

            $table->index('booking_id');
            $table->unique(['seat_id', 'showtime_id']);
            $table->index(['seat_id', 'showtime_id']);
            $table->index('qr_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
