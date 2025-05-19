<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('course_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('transaction_id')->unique();
            $table->string('status');
            $table->json('payment_details')->nullable();
            $table->dateTime('paid_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
