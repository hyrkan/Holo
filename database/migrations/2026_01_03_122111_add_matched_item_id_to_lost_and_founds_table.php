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
            $table->foreignId('matched_item_id')->nullable()->constrained('lost_and_founds')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lost_and_founds', function (Blueprint $table) {
            $table->dropConstrainedForeignId('matched_item_id');
        });
    }
};
