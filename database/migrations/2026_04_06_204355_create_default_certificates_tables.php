<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('default_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->text('body')->nullable();
            $table->string('background_image')->nullable();
            $table->boolean('is_selected')->default(false);
            $table->timestamps();
        });

        Schema::create('default_certificate_signatories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('default_certificate_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('label')->nullable();
            $table->string('signature_image')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_certificate_signatories');
        Schema::dropIfExists('default_certificates');
    }
};
