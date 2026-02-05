<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Liability extends Model
{
    protected $fillable = [
        'liability_number', 'name', 'type', 'principal_amount', 'outstanding_balance',
        'interest_rate', 'start_date', 'due_date', 'status', 'description',
        'account_id', 'supplier_id', 'user_id'
    ];

    protected $casts = [
        'principal_amount' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
