<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MessagingService
{
    private $bearerToken;
    private $baseUrl;
    private $fromNumber;

    public function __construct()
    {
        $this->bearerToken = 'f9a89f439206e27169ead766463ca92c';
        $this->baseUrl = 'https://messaging-service.co.tz/api/v2';
        $this->fromNumber = 'ShopSmart';
    }

    /**
     * Get SMS balance
     */
    public function getSmsBalance()
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->get($this->baseUrl . '/balance');

            if ($response->successful()) {
                $data = $response->json();
                
                // Cache balance for 5 minutes
                Cache::put('sms_balance', $data, now()->addMinutes(5));
                
                Log::info('SMS balance retrieved successfully', [
                    'balance' => $data['sms_balance'] ?? 0,
                    'response' => $data
                ]);
                
                return $data;
            } else {
                Log::error('Failed to get SMS balance', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return [
                    'error' => 'Failed to retrieve SMS balance',
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception getting SMS balance', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Failed to retrieve SMS balance: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get SMS logs with optional filters
     */
    public function getSmsLogs($filters = [])
    {
        try {
            $url = $this->baseUrl . '/logs';
            
            // Add query parameters
            if (!empty($filters)) {
                $queryParams = [];
                
                if (isset($filters['from'])) {
                    $queryParams['from'] = $filters['from'];
                }
                
                if (isset($filters['to'])) {
                    $queryParams['to'] = $filters['to'];
                }
                
                if (isset($filters['sentSince'])) {
                    $queryParams['sentSince'] = $filters['sentSince'];
                }
                
                if (isset($filters['sentUntil'])) {
                    $queryParams['sentUntil'] = $filters['sentUntil'];
                }
                
                if (isset($filters['limit'])) {
                    $queryParams['limit'] = min($filters['limit'], 500); // Max 500
                }
                
                if (isset($filters['offset'])) {
                    $queryParams['offset'] = $filters['offset'];
                }
                
                if (!empty($queryParams)) {
                    $url .= '?' . http_build_query($queryParams);
                }
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('SMS logs retrieved successfully', [
                    'filters' => $filters,
                    'count' => count($data['results'] ?? []),
                    'response' => $data
                ]);
                
                return $data;
            } else {
                Log::error('Failed to get SMS logs', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return [
                    'error' => 'Failed to retrieve SMS logs',
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception getting SMS logs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Failed to retrieve SMS logs: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send single SMS message
     */
    public function sendSms($to, $message, $reference = null)
    {
        try {
            $payload = [
                'from' => $this->fromNumber,
                'to' => $to,
                'text' => $message,
                'flash' => 0
            ];
            
            if ($reference) {
                $payload['reference'] = $reference;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($this->baseUrl . '/text/single', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('SMS sent successfully', [
                    'to' => $to,
                    'message' => $message,
                    'reference' => $reference,
                    'response' => $data
                ]);
                
                return $data;
            } else {
                Log::error('Failed to send SMS', [
                    'to' => $to,
                    'message' => $message,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return [
                    'error' => 'Failed to send SMS',
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception sending SMS', [
                'to' => $to,
                'message' => $message,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Failed to send SMS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send multiple SMS messages
     */
    public function sendMultipleSms($messages)
    {
        try {
            $payload = [
                'from' => $this->fromNumber,
                'messages' => $messages
            ];

            Log::info('Sending multiple SMS', [
                'payload' => $payload,
                'count' => count($messages)
            ]);

            // Use shorter timeout to prevent hanging
            $response = Http::timeout(15)  // Reduced from 30 to 15 seconds
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($this->baseUrl . '/text/multi', $payload);

            Log::info('SMS API response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body(),
                'timeout' => !$response->successful() && $response->clientError()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Multiple SMS sent successfully', [
                    'count' => count($messages),
                    'response' => $data
                ]);
                
                // Ensure success format
                return [
                    'success' => true,
                    'messages' => $data['messages'] ?? [],
                    'total_cost' => $data['total_cost'] ?? 0,
                    'currency' => $data['currency'] ?? 'TZS',
                    'api_response' => $data
                ];
            } else {
                Log::error('Failed to send multiple SMS', [
                    'count' => count($messages),
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'is_timeout' => $response->clientError(),
                    'is_server_error' => $response->serverError()
                ]);
                
                // Return error immediately to prevent hanging
                return [
                    'success' => false,
                    'error' => 'Failed to send multiple SMS',
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'is_timeout' => $response->clientError()
                ];
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection timeout sending multiple SMS', [
                'count' => count($messages),
                'error' => $e->getMessage(),
                'is_timeout' => true
            ]);
            
            return [
                'success' => false,
                'error' => 'Connection timeout: Failed to send multiple SMS',
                'is_timeout' => true
            ];
        } catch (\Exception $e) {
            Log::error('Exception sending multiple SMS', [
                'count' => count($messages),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => 'Failed to send multiple SMS: ' . $e->getMessage(),
                'is_exception' => true
            ];
        }
    }

    /**
     * Schedule SMS message
     */
    public function scheduleSms($to, $message, $date, $time, $reference = null)
    {
        try {
            $payload = [
                'from' => $this->fromNumber,
                'to' => $to,
                'text' => $message,
                'date' => $date,
                'time' => $time,
                'flash' => 0
            ];
            
            if ($reference) {
                $payload['reference'] = $reference;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($this->baseUrl . '/text/single', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('SMS scheduled successfully', [
                    'to' => $to,
                    'message' => $message,
                    'date' => $date,
                    'time' => $time,
                    'reference' => $reference,
                    'response' => $data
                ]);
                
                return $data;
            } else {
                Log::error('Failed to schedule SMS', [
                    'to' => $to,
                    'message' => $message,
                    'date' => $date,
                    'time' => $time,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return [
                    'error' => 'Failed to schedule SMS',
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception scheduling SMS', [
                'to' => $to,
                'message' => $message,
                'date' => $date,
                'time' => $time,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Failed to schedule SMS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get delivery reports
     */
    public function getDeliveryReports($sentSince = null, $sentUntil = null)
    {
        try {
            $url = $this->baseUrl . '/reports';
            
            if ($sentSince || $sentUntil) {
                $queryParams = [];
                
                if ($sentSince) {
                    $queryParams['sentSince'] = $sentSince;
                }
                
                if ($sentUntil) {
                    $queryParams['sentUntil'] = $sentUntil;
                }
                
                $url .= '?' . http_build_query($queryParams);
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Delivery reports retrieved successfully', [
                    'sentSince' => $sentSince,
                    'sentUntil' => $sentUntil,
                    'count' => count($data['results'] ?? []),
                    'response' => $data
                ]);
                
                return $data;
            } else {
                Log::error('Failed to get delivery reports', [
                    'sentSince' => $sentSince,
                    'sentUntil' => $sentUntil,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return [
                    'error' => 'Failed to retrieve delivery reports',
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception getting delivery reports', [
                'sentSince' => $sentSince,
                'sentUntil' => $sentUntil,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Failed to retrieve delivery reports: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Test connection to API
     */
    public function testConnection()
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->get($this->baseUrl . '/balance');

            if ($response->successful()) {
                Log::info('Messaging API connection test successful');
                return [
                    'success' => true,
                    'message' => 'Connection to Messaging Service API is working'
                ];
            } else {
                Log::error('Messaging API connection test failed', [
                    'status' => $response->status()
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to connect to Messaging Service API'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception testing messaging connection', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number
     */
    public static function formatPhoneNumber($phone)
    {
        // Remove any non-digit characters
        $phone = preg_replace('/\D/', '', $phone);
        
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
     * Validate phone number
     */
    public static function validatePhoneNumber($phone)
    {
        // Remove any non-digit characters
        $phone = preg_replace('/\D/', '', $phone);
        
        // Check if it's a valid Tanzanian number
        if (strlen($phone) === 9 && preg_match('/^[0-9]+$/', $phone)) {
            return true;
        }
        
        if (strlen($phone) === 12 && preg_match('/^255[0-9]+$/', $phone)) {
            return true;
        }
        
        return false;
    }
}
