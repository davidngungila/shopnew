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
        Schema::table('quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('quotations', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('quotation_date');
            }
            if (!Schema::hasColumn('quotations', 'terms_conditions')) {
                $table->text('terms_conditions')->nullable()->after('expiry_date');
            }
            if (!Schema::hasColumn('quotations', 'customer_notes')) {
                $table->text('customer_notes')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('quotations', 'is_sent')) {
                $table->boolean('is_sent')->default(false)->after('customer_notes');
            }
            if (!Schema::hasColumn('quotations', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('is_sent');
            }
            if (!Schema::hasColumn('quotations', 'converted_to_sale_id')) {
                $table->foreignId('converted_to_sale_id')->nullable()->after('sent_at')->constrained('sales')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (Schema::hasColumn('quotations', 'expiry_date')) {
                $table->dropColumn('expiry_date');
            }
            if (Schema::hasColumn('quotations', 'terms_conditions')) {
                $table->dropColumn('terms_conditions');
            }
            if (Schema::hasColumn('quotations', 'customer_notes')) {
                $table->dropColumn('customer_notes');
            }
            if (Schema::hasColumn('quotations', 'is_sent')) {
                $table->dropColumn('is_sent');
            }
            if (Schema::hasColumn('quotations', 'sent_at')) {
                $table->dropColumn('sent_at');
            }
            if (Schema::hasColumn('quotations', 'converted_to_sale_id')) {
                $table->dropForeign(['converted_to_sale_id']);
                $table->dropColumn('converted_to_sale_id');
            }
        });
    }
};

