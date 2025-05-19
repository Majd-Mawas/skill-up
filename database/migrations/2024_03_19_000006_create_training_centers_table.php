<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->foreignId('area_id')->constrained()->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_centers');
    }
};
