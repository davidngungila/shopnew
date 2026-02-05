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
        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->string('reconciliation_number')->unique();
            $table->foreignId('account_id')->constrained('chart_of_accounts');
            $table->date('statement_date');
            $table->decimal('bank_balance', 15, 2);
            $table->decimal('book_balance', 15, 2);
            $table->decimal('deposits_in_transit', 15, 2)->default(0);
            $table->decimal('outstanding_checks', 15, 2)->default(0);
            $table->decimal('bank_charges', 15, 2)->default(0);
            $table->decimal('interest_earned', 15, 2)->default(0);
            $table->decimal('adjusted_balance', 15, 2);
            $table->enum('status', ['pending', 'reconciled', 'discrepancy'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_reconciliations');
    }
};
