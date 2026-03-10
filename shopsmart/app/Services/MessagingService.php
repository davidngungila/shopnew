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
        // Hardcoded API configuration for messaging-service.co.tz
        $this->bearerToken = 'cedcce9becad866f59beac1fd5a235bc';
        $this->baseUrl = 'https://messaging-service.co.tz/api/sms/v2';
        $this->fromNumber = 'TANZANIATIP';
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
                
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception getting SMS balance', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
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
                
                Log::info('SMS logs retrieved successfully', [
                    'logs' => $data['logs'] ?? [],
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
     * Send SMS message
     */
    public function sendSms($to, $message, $reference = null)
    {
        try {
            $payload = [
                'from' => $this->fromNumber,
                'to' => $to,
                'text' => $message,
                'flash' => 0,
                'reference' => $reference ?? uniqid('sms_')
            ];

            Log::info('Sending SMS', [
                'url' => $this->baseUrl . '/text/single',
                'payload' => $payload,
                'headers' => [
                    'Authorization: Bearer ' . $this->bearerToken,
                    'Content-Type: application/json',
                    'Accept: application/json'
                ]
            ]);

            // Try with exact CURL-like approach
            $ch = curl_init();
            
            curl_setopt_array($ch, array(
                CURLOPT_URL => $this->baseUrl . '/text/single',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $this->bearerToken,
                    'Content-Type: application/json',
                    'Accept: application/json'
                ),
            ));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            Log::info('SMS API response', [
                'http_code' => $httpCode,
                'response' => $response,
                'curl_error' => $error
            ]);

            if ($httpCode >= 200 && $httpCode < 300) {
                $data = json_decode($response, true);
                
                Log::info('SMS sent successfully', [
                    'to' => $to,
                    'reference' => $payload['reference'],
                    'response' => $data
                ]);
                
                return $data;
            } else {
                Log::error('Failed to send SMS', [
                    'to' => $to,
                    'http_code' => $httpCode,
                    'response' => $response,
                    'curl_error' => $error
                ]);
                
                return [
                    'error' => 'Failed to send SMS',
                    'status' => $httpCode,
                    'response' => $response,
                    'curl_error' => $error
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception sending SMS', [
                'to' => $to,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Failed to send SMS: ' . $e->getMessage(),
                'exception' => true,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ];
        }
    }

    /**
     * Send multiple SMS messages
     */
    public function sendMultipleSms($messages)
    {
        try {
            // Handle both single message and array of messages
            if (isset($messages['from']) && isset($messages['messages'])) {
                // Already in correct format
                $payload = $messages;
            } else {
                // Convert to expected format
                $payload = [
                    'from' => $this->fromNumber,
                    'messages' => is_array($messages) ? $messages : [$messages]
                ];
            }

            Log::info('Sending multiple SMS', [
                'payload' => $payload,
                'count' => count($payload['messages'] ?? [])
            ]);

            // Use shorter timeout to prevent hanging
            $response = Http::timeout(15)
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
                    'count' => count($payload['messages'] ?? []),
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
                    'count' => count($payload['messages'] ?? []),
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
                'schedule_date' => $date,
                'schedule_time' => $time,
                'flash' => 0,
                'reference' => $reference ?? uniqid('sms_')
            ];

            Log::info('Scheduling SMS', [
                'payload' => $payload
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($this->baseUrl . '/schedule', $payload);

            Log::info('SMS Schedule response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('SMS scheduled successfully', [
                    'to' => $to,
                    'reference' => $payload['reference'],
                    'schedule_date' => $date,
                    'schedule_time' => $time,
                    'response' => $data
                ]);
                
                return $data;
            } else {
                Log::error('Failed to schedule SMS', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return [
                    'error' => 'Failed to schedule SMS',
                    'status' => $response->status(),
                    'response' => $response->body()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception scheduling SMS', [
                'to' => $to,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Failed to schedule SMS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get SMS delivery status
     */
    public function getSmsStatus($reference)
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->get($this->baseUrl . '/status/' . $reference);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('SMS status retrieved successfully', [
                    'reference' => $reference,
                    'response' => $data
                ]);
                
                return $data;
            } else {
                Log::error('Failed to get SMS status', [
                    'reference' => $reference,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception getting SMS status', [
                'reference' => $reference,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
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
