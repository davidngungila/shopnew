<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\PushSubscription;
use App\Models\NotificationHistory;
use App\Models\NotificationPreference;

class NotificationController extends Controller
{
    /**
     * Subscribe to push notifications
     */
    public function subscribe(Request $request)
    {
        try {
            $user = Auth::user();
            $subscriptionData = $request->validate([
                'endpoint' => 'required|string',
                'keys.p256dh' => 'required|string',
                'keys.auth' => 'required|string',
            ]);

            // Delete existing subscription for this user
            PushSubscription::where('user_id', $user->id)->delete();

            // Create new subscription
            $subscription = PushSubscription::create([
                'user_id' => $user->id,
                'endpoint' => $subscriptionData['endpoint'],
                'p256dh_key' => $subscriptionData['keys']['p256dh'],
                'auth_key' => $subscriptionData['keys']['auth'],
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to push notifications',
                'subscription_id' => $subscription->id
            ]);

        } catch (\Exception $e) {
            Log::error('Push subscription failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe to push notifications'
            ], 500);
        }
    }

    /**
     * Unsubscribe from push notifications
     */
    public function unsubscribe(Request $request)
    {
        try {
            $user = Auth::user();
            
            PushSubscription::where('user_id', $user->id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Successfully unsubscribed from push notifications'
            ]);

        } catch (\Exception $e) {
            Log::error('Push unsubscription failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to unsubscribe from push notifications'
            ], 500);
        }
    }

    /**
     * Get user's push subscriptions
     */
    public function getSubscriptions(Request $request)
    {
        try {
            $user = Auth::user();
            $subscriptions = PushSubscription::where('user_id', $user->id)->get();

            return response()->json([
                'success' => true,
                'subscriptions' => $subscriptions
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get subscriptions:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subscriptions'
            ], 500);
        }
    }

    /**
     * Send push notification
     */
    public function sendNotification(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'body' => 'required|string|max:1000',
                'icon' => 'nullable|string',
                'image' => 'nullable|string',
                'url' => 'nullable|string',
                'type' => 'nullable|string',
                'tag' => 'nullable|string',
                'require_interaction' => 'nullable|boolean',
                'silent' => 'nullable|boolean',
                'user_ids' => 'nullable|array',
                'user_ids.*' => 'integer',
                'send_to_all' => 'nullable|boolean'
            ]);

            $userIds = $data['user_ids'] ?? null;
            $sendToAll = $data['send_to_all'] ?? false;

            if ($sendToAll) {
                $subscriptions = PushSubscription::all();
            } elseif ($userIds) {
                $subscriptions = PushSubscription::whereIn('user_id', $userIds)->get();
            } else {
                $user = Auth::user();
                $subscriptions = PushSubscription::where('user_id', $user->id)->get();
            }

            $sentCount = $this->sendPushNotifications($subscriptions, $data);

            return response()->json([
                'success' => true,
                'message' => "Notification sent to {$sentCount} subscribers",
                'sent_count' => $sentCount
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send notification:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification'
            ], 500);
        }
    }

    /**
     * Send test notification
     */
    public function sendTestNotification(Request $request)
    {
        try {
            $user = Auth::user();
            $subscriptions = PushSubscription::where('user_id', $user->id)->get();

            if ($subscriptions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active subscriptions found'
                ]);
            }

            $notificationData = [
                'title' => 'Test Notification',
                'body' => 'This is a test notification from ShopSmart',
                'icon' => '/icons/icon-192x192.png',
                'tag' => 'test-notification',
                'type' => 'test'
            ];

            $sentCount = $this->sendPushNotifications($subscriptions, $notificationData);

            return response()->json([
                'success' => true,
                'message' => "Test notification sent to {$sentCount} devices",
                'sent_count' => $sentCount
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send test notification:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification'
            ], 500);
        }
    }

    /**
     * Mark notification as dismissed
     */
    public function markAsDismissed(Request $request)
    {
        try {
            $data = $request->validate([
                'notification_id' => 'required|string',
                'type' => 'nullable|string'
            ]);

            // Log notification dismissal for analytics
            NotificationHistory::create([
                'user_id' => Auth::id(),
                'notification_id' => $data['notification_id'],
                'type' => $data['type'] ?? 'unknown',
                'action' => 'dismissed',
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification dismissal recorded'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to record notification dismissal:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to record dismissal'
            ], 500);
        }
    }

    /**
     * Get notification history
     */
    public function getNotificationHistory(Request $request)
    {
        try {
            $user = Auth::user();
            $history = NotificationHistory::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(50);

            return response()->json([
                'success' => true,
                'history' => $history
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get notification history:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve notification history'
            ], 500);
        }
    }

    /**
     * Get notification preferences
     */
    public function getPreferences(Request $request)
    {
        try {
            $user = Auth::user();
            $preferences = NotificationPreference::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'inventory_alerts' => true,
                    'sales_updates' => true,
                    'purchase_orders' => true,
                    'system_updates' => false,
                    'marketing' => false
                ]
            );

            return response()->json([
                'success' => true,
                'preferences' => $preferences
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get notification preferences:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve preferences'
            ], 500);
        }
    }

    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request)
    {
        try {
            $user = Auth::user();
            $data = $request->validate([
                'inventory_alerts' => 'boolean',
                'sales_updates' => 'boolean',
                'purchase_orders' => 'boolean',
                'system_updates' => 'boolean',
                'marketing' => 'boolean'
            ]);

            $preferences = NotificationPreference::updateOrCreate(
                ['user_id' => $user->id],
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Preferences updated successfully',
                'preferences' => $preferences
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update notification preferences:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update preferences'
            ], 500);
        }
    }

    /**
     * Send push notifications to multiple subscriptions
     */
    private function sendPushNotifications($subscriptions, $data)
    {
        $sentCount = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $payload = [
                    'title' => $data['title'],
                    'body' => $data['body'],
                    'icon' => $data['icon'] ?? '/icons/icon-192x192.png',
                    'badge' => '/icons/icon-72x72.png',
                    'tag' => $data['tag'] ?? 'shopsmart-notification',
                    'requireInteraction' => $data['require_interaction'] ?? false,
                    'silent' => $data['silent'] ?? false,
                    'data' => [
                        'url' => $data['url'] ?? '/dashboard',
                        'type' => $data['type'] ?? 'general',
                        'id' => uniqid()
                    ]
                ];

                if (isset($data['image'])) {
                    $payload['image'] = $data['image'];
                }

                // Send notification using Web Push library
                $result = $this->sendWebPushNotification($subscription, $payload);

                if ($result) {
                    $sentCount++;

                    // Log sent notification
                    NotificationHistory::create([
                        'user_id' => $subscription->user_id,
                        'notification_id' => $payload['data']['id'],
                        'type' => $data['type'] ?? 'general',
                        'action' => 'sent',
                        'created_at' => now()
                    ]);
                }

            } catch (\Exception $e) {
                Log::error('Failed to send notification to subscription:', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $sentCount;
    }

    /**
     * Send web push notification to a single subscription
     */
    private function sendWebPushNotification($subscription, $payload)
    {
        // This is a placeholder implementation
        // In a real implementation, you would use a library like minishlink/web-push
        // For now, we'll just log the notification
        
        Log::info('Push notification sent:', [
            'subscription_id' => $subscription->id,
            'endpoint' => $subscription->endpoint,
            'payload' => $payload
        ]);

        return true; // Simulate successful send
    }
}
