<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MessagingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SmsController extends Controller
{
    protected $messagingService;

    public function __construct(MessagingService $messagingService)
    {
        $this->messagingService = $messagingService;
    }

    /**
     * Get SMS balance
     */
    public function balance(): JsonResponse
    {
        $result = $this->messagingService->getSmsBalance();
        
        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
                'data' => null
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'SMS balance retrieved successfully',
            'data' => $result
        ]);
    }

    /**
     * Get SMS logs
     */
    public function logs(Request $request): JsonResponse
    {
        $filters = $request->only([
            'from', 'to', 'sentSince', 'sentUntil', 'limit', 'offset'
        ]);
        
        $result = $this->messagingService->getSmsLogs($filters);
        
        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
                'data' => null
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'SMS logs retrieved successfully',
            'data' => $result
        ]);
    }

    /**
     * Send SMS
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => 'required|string',
            'message' => 'required|string|max:160',
            'reference' => 'nullable|string|max:50'
        ]);

        // Validate phone number
        if (!MessagingService::validatePhoneNumber($validated['to'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number format. Please use format: 255XXXXXXXXX or 0XXXXXXXXX',
                'data' => null
            ], 422);
        }

        // Format phone number
        $to = MessagingService::formatPhoneNumber($validated['to']);
        
        $result = $this->messagingService->sendSms(
            $to,
            $validated['message'],
            $validated['reference'] ?? null
        );
        
        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
                'data' => null
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'SMS sent successfully',
            'data' => $result
        ]);
    }

    /**
     * Send multiple SMS
     */
    public function sendMultiple(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'messages' => 'required|array|min:1|max:100',
            'messages.*.to' => 'required|string',
            'messages.*.message' => 'required|string|max:160'
        ]);

        // Format all phone numbers and validate
        $formattedMessages = [];
        foreach ($validated['messages'] as $messageData) {
            if (!MessagingService::validatePhoneNumber($messageData['to'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid phone number format: ' . $messageData['to'],
                    'data' => null
                ], 422);
            }
            
            $formattedMessages[] = [
                'to' => MessagingService::formatPhoneNumber($messageData['to']),
                'text' => $messageData['message']
            ];
        }
        
        $result = $this->messagingService->sendMultipleSms($formattedMessages);
        
        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
                'data' => null
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Multiple SMS sent successfully',
            'data' => $result
        ]);
    }

    /**
     * Schedule SMS
     */
    public function schedule(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => 'required|string',
            'message' => 'required|string|max:160',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'reference' => 'nullable|string|max:50'
        ]);

        // Validate phone number
        if (!MessagingService::validatePhoneNumber($validated['to'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number format. Please use format: 255XXXXXXXXX or 0XXXXXXXXX',
                'data' => null
            ], 422);
        }

        // Format phone number
        $to = MessagingService::formatPhoneNumber($validated['to']);
        
        $result = $this->messagingService->scheduleSms(
            $to,
            $validated['message'],
            $validated['date'],
            $validated['time'],
            $validated['reference'] ?? null
        );
        
        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
                'data' => null
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'SMS scheduled successfully',
            'data' => $result
        ]);
    }

    /**
     * Get delivery reports
     */
    public function deliveryReports(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sentSince' => 'nullable|date_format:Y-m-d',
            'sentUntil' => 'nullable|date_format:Y-m-d'
        ]);
        
        $result = $this->messagingService->getDeliveryReports(
            $validated['sentSince'] ?? null,
            $validated['sentUntil'] ?? null
        );
        
        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
                'data' => null
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Delivery reports retrieved successfully',
            'data' => $result
        ]);
    }

    /**
     * Test connection
     */
    public function testConnection(): JsonResponse
    {
        $result = $this->messagingService->testConnection();
        
        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'data' => $result
        ]);
    }
}
