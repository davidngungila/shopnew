<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseLocation extends Model
{
    protected $fillable = [
        'name',
        'location_type',
        'aisle',
        'rack',
        'shelf',
        'bin',
        'section',
        'zone',
        'description',
        'is_active',
        'warehouse_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'warehouse_id' => 'integer'
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
