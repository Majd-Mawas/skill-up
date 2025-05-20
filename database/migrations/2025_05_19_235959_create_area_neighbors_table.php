<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('area_neighbors', function (Blueprint $table) {
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->foreignId('neighbor_id')->constrained('areas')->cascadeOnDelete();

            $table->primary(['area_id', 'neighbor_id']);
            $table->timestamps();
        });

        Schema::table('area_neighbors', function (Blueprint $table) {
            DB::statement('ALTER TABLE area_neighbors ADD CONSTRAINT area_neighbors_order_check CHECK (area_id < neighbor_id)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('area_neighbors', function (Blueprint $table) {
            DB::statement('ALTER TABLE area_neighbors DROP CONSTRAINT area_neighbors_order_check');
        });
        Schema::dropIfExists('area_neighbors');
    }
};
