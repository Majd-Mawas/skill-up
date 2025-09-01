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
        // Schema::table('bookings', function (Blueprint $table) {
        //     $table->date('start_date')->nullable()->after('total_price');
        //     $table->date('end_date')->nullable()->after('start_date');
        // });

        // // Set default values for new columns
        // DB::statement('UPDATE bookings SET start_date = NOW(), end_date = NOW()');

        // // Make the columns required now that they have data
        // Schema::table('bookings', function (Blueprint $table) {
        //     $table->date('start_date')->nullable(false)->change();
        //     $table->date('end_date')->nullable(false)->change();
        // });

        // Add legacy_date column if needed
        if (Schema::hasColumn('bookings', 'date')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->renameColumn('date', 'legacy_date');
            });
        } else {
            Schema::table('bookings', function (Blueprint $table) {
                $table->date('legacy_date')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Handle legacy_date column in down method
        if (Schema::hasColumn('bookings', 'legacy_date')) {
            if (!Schema::hasColumn('bookings', 'date')) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->renameColumn('legacy_date', 'date');
                });
            } else {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->dropColumn('legacy_date');
                });
            }
        }

        // Then drop the new columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
