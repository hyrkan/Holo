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
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('target_audience')->default('all')->after('image'); // all, students, guests
            $table->json('target_year_levels')->nullable()->after('target_audience'); // ['1st Year', '2nd Year', etc.]
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['target_audience', 'target_year_levels']);
        });
    }
};
