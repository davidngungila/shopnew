<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('warehouse_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location_type');
            $table->string('aisle')->nullable();
            $table->string('rack')->nullable();
            $table->string('shelf')->nullable();
            $table->string('bin')->nullable();
            $table->string('section')->nullable();
            $table->string('zone')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('warehouse_id')->constrained();
            $table->timestamps();
            
            $table->index(['warehouse_id', 'location_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_locations');
    }
};
