<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number', 'customer_id', 'user_id', 'warehouse_id',
        'subtotal', 'discount', 'tax', 'total', 'payment_method',
        'status', 'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SalePayment::class);
    }

    public function getTotalPaidAttribute()
    {
        if ($this->relationLoaded('payments')) {
            return $this->payments->sum('amount');
        }
        return $this->payments()->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return $this->total - $this->total_paid;
    }

    public function isFullyPaid()
    {
        return $this->balance <= 0;
    }
}
