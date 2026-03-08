<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('id_front_path')->nullable()->after('expired_at');
            $table->string('id_back_path')->nullable()->after('id_front_path');
            $table->string('face_photo_path')->nullable()->after('id_back_path');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['id_front_path', 'id_back_path', 'face_photo_path']);
        });
    }
};
