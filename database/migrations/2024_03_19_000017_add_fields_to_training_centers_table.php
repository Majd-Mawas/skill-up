<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_centers', function (Blueprint $table) {
            $table->string('phone_number')->after('address');
            $table->string('email')->after('phone_number');
            $table->string('status')->default('active')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('training_centers', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'email', 'status']);
        });
    }
};
