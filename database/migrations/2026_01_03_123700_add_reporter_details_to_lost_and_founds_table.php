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
        Schema::table('lost_and_founds', function (Blueprint $table) {
            $table->string('reporter_name')->nullable()->after('user_id');
            $table->string('owner_name')->nullable()->after('reporter_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lost_and_founds', function (Blueprint $table) {
            $table->dropColumn(['reporter_name', 'owner_name']);
        });
    }
};
