<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('placement_test_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_test_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Add a unique constraint to prevent duplicate associations
            $table->unique(['placement_test_id', 'booking_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('placement_test_bookings');
    }
};