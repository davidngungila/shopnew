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
        Schema::create('liabilities', function (Blueprint $table) {
            $table->id();
            $table->string('liability_number')->unique();
            $table->string('name');
            $table->enum('type', ['loan', 'credit', 'payable', 'other'])->default('loan');
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('outstanding_balance', 15, 2);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->date('start_date');
            $table->date('due_date')->nullable();
            $table->enum('status', ['active', 'paid', 'overdue'])->default('active');
            $table->text('description')->nullable();
            $table->foreignId('account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liabilities');
    }
};
