<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('hall_id')->constrained()->onDelete('restrict');
            $table->foreignId('trainer_id')->constrained('users')->onDelete('restrict');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
