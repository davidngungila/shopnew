<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'username', // Bearer token stored here
        'password',
        'from',
        'api_url',
        'active',
        'is_primary',
        'config',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_primary' => 'boolean',
        'config' => 'array',
    ];

    /**
     * Get the primary SMS provider
     */
    public static function getPrimary(): ?self
    {
        return static::where('is_primary', true)
            ->where('active', true)
            ->first();
    }

    /**
     * Get active SMS providers
     */
    public static function getActive()
    {
        return static::where('active', true)->get();
    }

    /**
     * Set this provider as primary
     */
    public function setAsPrimary(): void
    {
        // Remove primary status from all other providers
        static::where('id', '!=', $this->id)
            ->update(['is_primary' => false]);
        
        // Set this provider as primary
        $this->update(['is_primary' => true]);
    }

    /**
     * Get formatted API URL
     */
    public function getFormattedApiUrlAttribute(): string
    {
        $url = $this->api_url;
        
        // Normalize API URL to use correct endpoint
        if (strpos($url, '/link/sms') !== false) {
            return 'https://messaging-service.co.tz/api/sms/v2/text/single';
        }
        
        if (strpos($url, '/api/sms') !== false) {
            if (strpos($url, '/v1/') !== false) {
                return str_replace('/v1/', '/v2/', $url);
            } elseif (strpos($url, '/v2/') === false && strpos($url, '/text/') === false) {
                return rtrim($url, '/') . '/v2/text/single';
            }
        }
        
        return $url;
    }

    /**
     * Get bearer token
     */
    public function getBearerTokenAttribute(): string
    {
        return trim($this->username);
    }

    /**
     * Check if provider is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->username) && !empty($this->api_url);
    }

    /**
     * Get provider status for UI
     */
    public function getStatusAttribute(): string
    {
        if (!$this->active) {
            return 'Inactive';
        }
        
        if ($this->is_primary) {
            return 'Primary';
        }
        
        return 'Active';
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Primary' => 'green',
            'Active' => 'blue',
            'Inactive' => 'red',
            default => 'gray',
        };
    }

    /**
     * Test connection to provider
     */
    public function testConnection(): array
    {
        try {
            $bearerToken = $this->bearer_token;
            
            if (empty($bearerToken)) {
                return [
                    'success' => false,
                    'error' => 'Bearer token is missing'
                ];
            }

            $response = \Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($this->formatted_api_url, [
                    'from' => $this->from,
                    'to' => '255123456789', // Test number
                    'text' => 'Connection test from ShopSmart',
                    'flash' => 0,
                    'reference' => 'connection_test_' . time()
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['messages']) && is_array($responseData['messages']) && count($responseData['messages']) > 0) {
                    $messageStatus = $responseData['messages'][0]['status'] ?? [];
                    
                    if (isset($messageStatus['groupName']) && $messageStatus['groupName'] === 'REJECTED') {
                        return [
                            'success' => false,
                            'error' => $messageStatus['description'] ?? $messageStatus['name'] ?? 'Message rejected'
                        ];
                    }
                    
                    return [
                        'success' => true,
                        'message' => 'Connection test successful!'
                    ];
                }
                
                return [
                    'success' => true,
                    'message' => 'Connection test successful!'
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to connect: ' . $response->status()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }
}
