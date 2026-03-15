<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lost_and_founds', function ($table) {
            $table->enum('status', ['pending', 'active', 'resolved'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lost_and_founds', function ($table) {
            $table->enum('status', ['active', 'resolved'])->default('active')->change();
        });
    }
};

