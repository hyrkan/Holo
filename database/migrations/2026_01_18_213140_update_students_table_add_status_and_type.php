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
            $table->string('status')->default('pending')->after('year_level'); // pending, approved, denied, expired
            $table->string('student_type')->default('regular')->after('status'); // regular, guest
            $table->timestamp('approved_at')->nullable()->after('student_type');
            $table->timestamp('expired_at')->nullable()->after('approved_at');
            $table->string('program')->nullable()->change();
            $table->string('year_level')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('program')->nullable(false)->change();
            $table->string('year_level')->nullable(false)->change();
            $table->dropColumn(['status', 'student_type', 'approved_at', 'expired_at']);
        });
    }
};
