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
        if (!Schema::hasColumn('lost_and_founds', 'returned_by_name')) {
            $table->string('returned_by_name')->nullable();
        }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lost_and_founds', function (Blueprint $table) {
            $table->dropColumn('returned_by_name');
        });
    }
};
