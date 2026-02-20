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
        Schema::create('communication_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Configuration name
            $table->enum('type', ['email', 'sms']); // Configuration type
            $table->boolean('is_primary')->default(false); // Primary configuration
            $table->boolean('is_active')->default(true); // Active status
            $table->json('config'); // Configuration data stored as JSON
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['type', 'is_primary']);
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_configs');
    }
};







