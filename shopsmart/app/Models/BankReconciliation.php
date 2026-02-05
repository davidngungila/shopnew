<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankReconciliation extends Model
{
    protected $fillable = [
        'reconciliation_number', 'account_id', 'statement_date', 'bank_balance',
        'book_balance', 'deposits_in_transit', 'outstanding_checks', 'bank_charges',
        'interest_earned', 'adjusted_balance', 'status', 'notes', 'user_id'
    ];

    protected $casts = [
        'statement_date' => 'date',
        'bank_balance' => 'decimal:2',
        'book_balance' => 'decimal:2',
        'deposits_in_transit' => 'decimal:2',
        'outstanding_checks' => 'decimal:2',
        'bank_charges' => 'decimal:2',
        'interest_earned' => 'decimal:2',
        'adjusted_balance' => 'decimal:2',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
