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
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE lost_and_founds DROP CONSTRAINT IF EXISTS lost_and_founds_status_check');
            DB::statement("ALTER TABLE lost_and_founds ALTER COLUMN status TYPE VARCHAR(255) USING status::VARCHAR");
            DB::statement("ALTER TABLE lost_and_founds ALTER COLUMN status SET DEFAULT 'pending'");
            DB::statement("ALTER TABLE lost_and_founds ADD CONSTRAINT lost_and_founds_status_check CHECK (status IN ('pending', 'active', 'resolved'))");
        } else {
            Schema::table('lost_and_founds', function ($table) {
                $table->enum('status', ['pending', 'active', 'resolved'])->default('pending')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE lost_and_founds DROP CONSTRAINT IF EXISTS lost_and_founds_status_check');
            DB::statement("ALTER TABLE lost_and_founds ALTER COLUMN status TYPE VARCHAR(255) USING status::VARCHAR");
            DB::statement("ALTER TABLE lost_and_founds ALTER COLUMN status SET DEFAULT 'active'");
            DB::statement("ALTER TABLE lost_and_founds ADD CONSTRAINT lost_and_founds_status_check CHECK (status IN ('active', 'resolved'))");
        } else {
            Schema::table('lost_and_founds', function ($table) {
                $table->enum('status', ['active', 'resolved'])->default('active')->change();
            });
        }
    }
};
