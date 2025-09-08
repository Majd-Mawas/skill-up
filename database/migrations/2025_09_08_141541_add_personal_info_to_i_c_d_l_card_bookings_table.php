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
        Schema::table('i_c_d_l_card_bookings', function (Blueprint $table) {
            $table->string('full_name_arabic')->nullable();
            $table->string('full_name_english')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('national_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('i_c_d_l_card_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'full_name_arabic',
                'full_name_english',
                'birth_date',
                'national_id',
            ]);
        });
    }
};
