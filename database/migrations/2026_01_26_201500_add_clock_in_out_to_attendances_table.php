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
        Schema::table('attendances', function (Blueprint $table) {
        if (!Schema::hasColumn('attendances', 'clock_in')) {
            $table->timestamp('clock_in')->nullable();
        }
        if (!Schema::hasColumn('attendances', 'clock_out')) {
            $table->timestamp('clock_out')->nullable(); // assumes clock_in exists now or before
        }
        if (!Schema::hasColumn('attendances', 'photo')) {
            $table->string('photo')->nullable(); // assumes clock_out exists now or before
        }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['clock_in', 'clock_out', 'photo']);
        });
    }
};
