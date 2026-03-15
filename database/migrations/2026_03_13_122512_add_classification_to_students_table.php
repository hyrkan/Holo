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
        Schema::table('students', function (Blueprint $table) {
            // Program dropdown options (BSCS, BSIT, etc.)
            // Already exists as free-text, no change to column type needed.

            // Enrollment status: enrolled or graduate
            $table->string('enrollment_status')->nullable()->after('year_level');

            // Classification: freshie, cross_enrollee, enrolled
            $table->string('classification')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['enrollment_status', 'classification']);
        });
    }
};
