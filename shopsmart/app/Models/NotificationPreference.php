<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'inventory_alerts',
        'sales_updates',
        'purchase_orders',
        'system_updates',
        'marketing',
        'custom_preferences'
    ];

    protected $casts = [
        'inventory_alerts' => 'boolean',
        'sales_updates' => 'boolean',
        'purchase_orders' => 'boolean',
        'system_updates' => 'boolean',
        'marketing' => 'boolean',
        'custom_preferences' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
