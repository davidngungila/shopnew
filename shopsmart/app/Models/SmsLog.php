<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'reference',
        'from',
        'to',
        'message',
        'channel',
        'sms_count',
        'status_group_id',
        'status_group_name',
        'status_id',
        'status_name',
        'status_description',
        'sent_at',
        'done_at',
        'delivery',
        'api_response',
        'success',
        'error_message',
        'user_id',
        'sent_by_user_id',
        'template_id',
        'saving_behavior',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'done_at' => 'datetime',
        'delivery' => 'array',
        'api_response' => 'array',
        'success' => 'boolean',
    ];

    /**
     * Get the user who sent this SMS
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who triggered sending this SMS
     */
    public function sentByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by_user_id');
    }

    /**
     * Get the template used for this SMS
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(SmsTemplate::class);
    }

    /**
     * Scope a query to only include successful SMS
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    /**
     * Scope a query to only include failed SMS
     */
    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    /**
     * Scope a query to only include SMS with specific status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status_group_name', $status);
    }

    /**
     * Scope a query to only include SMS sent after a specific date
     */
    public function scopeSentAfter($query, $date)
    {
        return $query->where('sent_at', '>=', $date);
    }

    /**
     * Scope a query to only include SMS sent before a specific date
     */
    public function scopeSentBefore($query, $date)
    {
        return $query->where('sent_at', '<=', $date);
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute(): string
    {
        $phone = $this->to;
        
        // Add country code if missing
        if (strlen($phone) === 9 && strpos($phone, '0') === 0) {
            return '255' . substr($phone, 1);
        }
        
        if (strlen($phone) === 9 && strpos($phone, '0') !== 0) {
            return '255' . $phone;
        }
        
        return $phone;
    }

    /**
     * Get short message preview
     */
    public function getShortMessageAttribute(): string
    {
        return strlen($this->message) > 50 ? substr($this->message, 0, 47) . '...' : $this->message;
    }

    /**
     * Check if SMS was delivered successfully
     */
    public function isDelivered(): bool
    {
        return $this->status_group_name === 'DELIVERED';
    }

    /**
     * Check if SMS is still pending
     */
    public function isPending(): bool
    {
        return $this->status_group_name === 'PENDING';
    }

    /**
     * Check if SMS was rejected
     */
    public function isRejected(): bool
    {
        return $this->status_group_name === 'REJECTED';
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status_group_name) {
            'DELIVERED' => 'green',
            'PENDING' => 'yellow',
            'REJECTED' => 'red',
            'ACCEPTED' => 'blue',
            default => 'gray',
        };
    }

    /**
     * Get duration in seconds
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->sent_at || !$this->done_at) {
            return null;
        }
        
        return $this->sent_at->diffInSeconds($this->done_at);
    }
}
