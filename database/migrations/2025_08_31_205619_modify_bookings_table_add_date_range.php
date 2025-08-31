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
        // First add the new columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('end_time');
            $table->date('end_date')->nullable()->after('start_date');
        });
        
        // Update existing records to copy date to start_date and end_date
        DB::statement('UPDATE bookings SET start_date = date, end_date = date');
        
        // Make the columns required now that they have data
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('start_date')->nullable(false)->change();
            $table->date('end_date')->nullable(false)->change();
        });
        
        // Rename the old column
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('date', 'legacy_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First rename the legacy_date back to date
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('legacy_date', 'date');
        });
        
        // Then drop the new columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
