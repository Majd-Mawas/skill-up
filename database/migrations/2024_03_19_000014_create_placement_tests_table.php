<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('placement_tests', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->foreignId('evaluator_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('training_center_id')->constrained()->onDelete('restrict');
            // $table->dateTime('test_date');
            // $table->integer('score');
            $table->string('name');
            // $table->string('status');
            // $table->dateTime('evaluation_date');
            // $table->json('recommended_courses')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('placement_tests');
    }
};
