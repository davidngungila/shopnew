@extends('layouts.app')

@section('title', 'Email Configuration')

@section('content')
<div class="space-y-6" x-data="emailConfig()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Email Configuration</h1>
            <p class="text-gray-600 mt-1">Configure email settings and integrate with messaging service API</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('settings.communication') }}" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors" style="background-color: #6b7280;">
                <i class="fas fa-arrow-left mr-2"></i>Back to Communication
            </a>
            <button @click="testConfiguration()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-vial mr-2"></i>Test Configuration
            </button>
        </div>
    </div>

    <!-- Status Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- API Documentation Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl mt-1"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-blue-900">Messaging Service API Integration</h3>
                <p class="text-blue-700 text-sm mt-1">
                    This configuration integrates with <strong>Messaging Service API V2</strong> for sending emails. 
                    All requests are submitted via HTTP POST to the base URL with proper authentication.
                </p>
            </div>
        </div>
    </div>

    <!-- Configuration Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Email Configuration</h2>
        </div>
        
        <form action="{{ route('settings.communication.email.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Basic Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1 text-blue-500"></i>Configuration Name
                    </label>
                    <input type="text" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Primary Email, Notifications Email" 
                           x-model="config.name">
                    <p class="text-xs text-gray-500 mt-1">A descriptive name for this email configuration</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1 text-green-500"></i>From Email Address
                    </label>
                    <input type="email" name="from_email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="noreply@yourcompany.com" 
                           x-model="config.from_email">
                    <p class="text-xs text-gray-500 mt-1">Email address that will appear as sender</p>
                </div>
            </div>

            <!-- SMTP Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-server mr-2 text-purple-500"></i>
                    SMTP Server Configuration
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                        <input type="text" name="smtp_host" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="smtp.gmail.com" 
                               x-model="config.smtp_host">
                        <p class="text-xs text-gray-500 mt-1">Your SMTP server hostname</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                        <input type="number" name="smtp_port" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="587" 
                               x-model="config.smtp_port">
                        <p class="text-xs text-gray-500 mt-1">SMTP server port (usually 587, 465, or 25)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" name="smtp_username" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="your-email@gmail.com" 
                               x-model="config.smtp_username">
                        <p class="text-xs text-gray-500 mt-1">SMTP authentication username</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="smtp_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Your SMTP password" 
                               x-model="config.smtp_password">
                        <p class="text-xs text-gray-500 mt-1">SMTP authentication password</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                        <select name="encryption" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                x-model="config.encryption">
                            <option value="tls">TLS</option>
                            <option value="ssl">SSL</option>
                            <option value="none">None</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">SMTP encryption method</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Timeout (seconds)</label>
                        <input type="number" name="timeout" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="30" 
                               x-model="config.timeout">
                        <p class="text-xs text-gray-500 mt-1">Connection timeout in seconds</p>
                    </div>
                </div>
            </div>

            <!-- API Integration Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-plug mr-2 text-orange-500"></i>
                    Messaging Service API Integration
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">API Base URL</label>
                        <input type="url" name="api_base_url" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               value="https://messaging-service.co.tz" 
                               x-model="config.api_base_url">
                        <p class="text-xs text-gray-500 mt-1">Base URL for Messaging Service API</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">API Token</label>
                            <div class="relative">
                                <input type="password" name="api_token" required
                                       class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                       placeholder="YOUR_ACCESS_TOKEN" 
                                       x-model="config.api_token">
                                <button type="button" @click="toggleTokenVisibility()" 
                                        class="absolute inset-y-0 right-0 px-3 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
                                    <i :class="showToken ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Bearer token for API authentication</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sender ID</label>
                            <input type="text" name="sender_id" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="TANZANIATIP" 
                                   x-model="config.sender_id">
                            <p class="text-xs text-gray-500 mt-1">Registered sender ID for messaging service</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-cog mr-2 text-gray-500"></i>
                    Additional Settings
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_primary" value="1" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   x-model="config.is_primary">
                            <label for="is_primary" class="ml-2 text-sm font-medium text-gray-700">
                                Set as Primary Configuration
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">Mark this as the default email configuration</p>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <input type="checkbox" name="enable_test_mode" value="1" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   x-model="config.enable_test_mode">
                            <label for="enable_test_mode" class="ml-2 text-sm font-medium text-gray-700">
                                Enable Test Mode
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">Send test emails instead of real ones</p>
                    </div>
                </div>
            </div>

            <!-- Test Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-vial mr-2 text-green-500"></i>
                    Test Configuration
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Email Address</label>
                        <input type="email" name="test_email" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="test@example.com" 
                               x-model="testEmail">
                        <p class="text-xs text-gray-500 mt-1">Email address to send test message</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Message</label>
                        <textarea name="test_message" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="This is a test email to verify your configuration." 
                                  x-model="testMessage"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Custom test message content</p>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" @click="sendTestEmail()" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Send Test Email
                    </button>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('settings.communication') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 text-white rounded-lg hover:bg-blue-700 transition-colors" 
                        style="background-color: #009245;">
                    <i class="fas fa-save mr-2"></i>Save Configuration
                </button>
            </div>
        </form>
    </div>

    <!-- API Instructions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-code mr-2 text-indigo-500"></i>
            API Integration Instructions
        </h3>
        
        <div class="space-y-6">
            <!-- Authentication -->
            <div>
                <h4 class="text-md font-medium text-gray-800 mb-2">Authentication</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-700 mb-2">All API requests must include proper authentication:</p>
                    <div class="bg-gray-900 text-gray-100 p-3 rounded font-mono text-sm">
                        <strong>Authorization: Bearer YOUR_ACCESS_TOKEN</strong>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Replace YOUR_ACCESS_TOKEN with your actual API token from the messaging service dashboard.
                    </p>
                </div>
            </div>

            <!-- Request Format -->
            <div>
                <h4 class="text-md font-medium text-gray-800 mb-2">Request Format</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-700 mb-2">HTTP POST request with JSON payload:</p>
                    <div class="bg-gray-900 text-gray-100 p-3 rounded font-mono text-sm overflow-x-auto">
                        <pre>POST https://messaging-service.co.tz/api/email/v2/text/single
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_ACCESS_TOKEN

{
    "from": "TANZANIATIP",
    "to": "recipient@example.com",
    "subject": "Test Subject",
    "text": "This is a test email message."
}</pre>
                    </div>
                </div>
            </div>

            <!-- Response Handling -->
            <div>
                <h4 class="text-md font-medium text-gray-800 mb-2">Response Handling</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-700 mb-2">Expected response format:</p>
                    <div class="bg-gray-900 text-gray-100 p-3 rounded font-mono text-sm overflow-x-auto">
                        <pre>{
    "messages": [
        {
            "to": "recipient@example.com",
            "status": {
                "groupId": 18,
                "groupName": "PENDING",
                "id": 51,
                "name": "ENROUTE (SENT)",
                "description": "Message sent to next instance"
            },
            "messageId": "unique-message-id",
            "message": "Your email content here",
            "smsCount": 0,
            "price": 0
        }
    ]
}</pre>
                    </div>
                </div>
            </div>

            <!-- Error Codes -->
            <div>
                <h4 class="text-md font-medium text-gray-800 mb-2">Common Error Codes</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="font-medium">200 OK</span>
                            <span class="text-green-600">Request successful</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">401 Unauthorized</span>
                            <span class="text-red-600">Invalid or missing token</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">400 Bad Request</span>
                            <span class="text-orange-600">Invalid request format</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">500 Server Error</span>
                            <span class="text-red-600">Internal server error</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function emailConfig() {
    return {
        config: {
            name: '',
            from_email: '',
            smtp_host: '',
            smtp_port: 587,
            smtp_username: '',
            smtp_password: '',
            encryption: 'tls',
            timeout: 30,
            api_base_url: 'https://messaging-service.co.tz',
            api_token: '',
            sender_id: 'TANZANIATIP',
            is_primary: false,
            enable_test_mode: false
        },
        testEmail: 'test@example.com',
        testMessage: 'This is a test email to verify your email configuration.',
        showToken: false,
        
        toggleTokenVisibility() {
            this.showToken = !this.showToken;
        },
        
        testConfiguration() {
            if (!this.config.api_token || !this.config.from_email) {
                alert('Please configure API token and from email address first.');
                return;
            }
            
            alert('Testing email configuration...\n\nThis will send a test email using your configured settings.');
        },
        
        sendTestEmail() {
            if (!this.testEmail) {
                alert('Please enter a test email address.');
                return;
            }
            
            // Simulate API call
            const payload = {
                from: this.config.sender_id,
                to: this.testEmail,
                subject: 'Test Email from ShopSmart',
                text: this.testMessage
            };
            
            console.log('Sending test email:', payload);
            
            // In a real implementation, this would make an actual API call
            fetch(`${this.config.api_base_url}/api/email/v2/test/text/single`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.config.api_token}`
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages[0]) {
                    const status = data.messages[0].status;
                    if (status.groupId === 18) {
                        alert('✅ Test email sent successfully!\n\nMessage ID: ' + data.messages[0].messageId + '\nStatus: ' + status.name);
                    } else {
                        alert('❌ Test email failed!\n\nStatus: ' + status.name + '\nDescription: ' + status.description);
                    }
                } else {
                    alert('❌ Test email failed!\n\nUnexpected response format.');
                }
            })
            .catch(error => {
                alert('❌ Test email failed!\n\nError: ' + error.message);
            });
        }
    }
}
</script>
@endsection
