<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'content',
        'variables',
        'category',
        'active',
        'usage_count',
    ];

    protected $casts = [
        'variables' => 'array',
        'active' => 'boolean',
    ];

    /**
     * Get SMS logs that used this template
     */
    public function smsLogs(): HasMany
    {
        return $this->hasMany(SmsLog::class);
    }

    /**
     * Get active templates
     */
    public static function getActive()
    {
        return static::where('active', true)->orderBy('name')->get();
    }

    /**
     * Get templates by category
     */
    public static function getByCategory(string $category)
    {
        return static::where('category', $category)
            ->where('active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Process template with variables
     */
    public function processContent(array $variables = []): string
    {
        $content = $this->content;
        
        foreach ($variables as $key => $value) {
            $content = str_replace('{' . $key . '}', $value, $content);
        }
        
        return $content;
    }

    /**
     * Get template variables as array
     */
    public function getVariablesArrayAttribute(): array
    {
        return $this->variables ?? [];
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get popular templates
     */
    public static function getPopular(int $limit = 5)
    {
        return static::where('active', true)
            ->orderByDesc('usage_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Search templates by name or content
     */
    public static function search(string $query)
    {
        return static::where('active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('content', 'like', '%' . $query . '%');
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Create default templates
     */
    public static function createDefaults(): void
    {
        $templates = [
            [
                'name' => 'Welcome Message',
                'description' => 'Welcome message for new users',
                'content' => 'Welcome to ShopSmart! Your account has been created successfully. Thank you for joining us!',
                'variables' => ['user_name', 'company_name'],
                'category' => 'welcome',
                'active' => true,
            ],
            [
                'name' => 'Verification Code',
                'description' => 'SMS verification code for user authentication',
                'content' => 'Your ShopSmart verification code is: {verification_code}. This code will expire in 10 minutes.',
                'variables' => ['verification_code'],
                'category' => 'verification',
                'active' => true,
            ],
            [
                'name' => 'Appointment Reminder',
                'description' => 'Reminder for upcoming appointments',
                'content' => 'Reminder: You have an appointment scheduled for {appointment_date} at {appointment_time}. Please be on time.',
                'variables' => ['user_name', 'appointment_date', 'appointment_time'],
                'category' => 'appointment',
                'active' => true,
            ],
            [
                'name' => 'Payment Confirmation',
                'description' => 'Confirmation of successful payment',
                'content' => 'Payment of {amount} TZS received successfully for order #{order_id}. Thank you for your purchase!',
                'variables' => ['user_name', 'amount', 'order_id'],
                'category' => 'payment',
                'active' => true,
            ],
            [
                'name' => 'Promotional Message',
                'description' => 'General promotional message',
                'content' => 'Special offer! Get {discount}% off on all products. Use code {promo_code}. Valid until {expiry_date}.',
                'variables' => ['user_name', 'discount', 'promo_code', 'expiry_date'],
                'category' => 'promotion',
                'active' => true,
            ],
        ];

        foreach ($templates as $template) {
            static::create($template);
        }
    }

    /**
     * Get available categories
     */
    public static function getCategories(): array
    {
        return [
            'welcome' => 'Welcome Messages',
            'verification' => 'Verification Codes',
            'appointment' => 'Appointment Reminders',
            'payment' => 'Payment Confirmations',
            'promotion' => 'Promotional Messages',
            'notification' => 'General Notifications',
            'alert' => 'Alert Messages',
        ];
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        return self::getCategories()[$this->category] ?? 'Other';
    }
}
