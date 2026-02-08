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
        if (Schema::hasTable('quotations')) {
            Schema::table('quotations', function (Blueprint $table) {
                // Add customer_id if missing
                if (!Schema::hasColumn('quotations', 'customer_id')) {
                    if (Schema::hasTable('customers')) {
                        $table->foreignId('customer_id')->nullable()->after('quotation_number')->constrained('customers')->nullOnDelete();
                    } else {
                        $table->unsignedBigInteger('customer_id')->nullable()->after('quotation_number');
                    }
                }
                
                // Add user_id if missing
                if (!Schema::hasColumn('quotations', 'user_id')) {
                    if (Schema::hasTable('users')) {
                        $table->foreignId('user_id')->nullable()->after('customer_id')->constrained('users')->cascadeOnDelete();
                    } else {
                        $table->unsignedBigInteger('user_id')->nullable()->after('customer_id');
                    }
                }
                
                // Add warehouse_id if missing
                if (!Schema::hasColumn('quotations', 'warehouse_id')) {
                    if (Schema::hasTable('warehouses')) {
                        $table->foreignId('warehouse_id')->nullable()->after('user_id')->constrained('warehouses')->nullOnDelete();
                    } else {
                        $table->unsignedBigInteger('warehouse_id')->nullable()->after('user_id');
                    }
                }
                
                // Add quotation_number if missing
                if (!Schema::hasColumn('quotations', 'quotation_number')) {
                    $table->string('quotation_number')->unique()->after('id');
                }
                
                // Add status if missing
                if (!Schema::hasColumn('quotations', 'status')) {
                    $table->enum('status', ['pending', 'approved', 'rejected', 'expired', 'converted'])->default('pending')->after('total');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop columns in down() to avoid data loss
        // If needed, columns can be dropped manually
    }
};



