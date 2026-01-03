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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->text('body')->nullable();
            $table->string('background_image')->nullable();
            $table->string('signatory_1_name')->nullable();
            $table->string('signatory_1_label')->nullable();
            $table->string('signatory_2_name')->nullable();
            $table->string('signatory_2_label')->nullable();
            $table->string('signatory_3_name')->nullable();
            $table->string('signatory_3_label')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
