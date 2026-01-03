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
        Schema::create('lost_and_founds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name');
            $table->text('description');
            $table->string('location');
            $table->timestamp('date_reported')->useCurrent();
            $table->enum('type', ['lost', 'found'])->default('lost');
            $table->enum('status', ['active', 'resolved'])->default('active');
            $table->boolean('is_anonymous')->default(false);
            $table->string('image_path')->nullable();
            $table->string('contact_info')->nullable();
            $table->string('handover_image_path')->nullable();
            $table->string('identity_proof_ref')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_and_founds');
    }
};
