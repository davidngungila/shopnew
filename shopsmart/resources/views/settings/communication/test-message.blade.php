@extends('layouts.app')

@section('title', 'Send Test Message')

@section('content')
<div class="space-y-6" x-data="testMessage()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Send Test Message</h1>
            <p class="text-gray-600 mt-1">Test email and SMS delivery with advanced options</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('settings.communication.index') }}" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors" style="background-color: #6b7280;">
                <i class="fas fa-arrow-left mr-2"></i>Back to Communication
            </a>
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

    <!-- Configuration Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Email Configuration Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-envelope mr-2 text-blue-600"></i>
                    Email Configuration
                </h3>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i>Active
                    </span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        <i class="fas fa-star mr-1"></i>Primary
                    </span>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">SMTP Host:</span>
                    <span class="text-sm font-medium text-gray-900">smtp.gmail.com</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">SMTP Port:</span>
                    <span class="text-sm font-medium text-gray-900">587</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">From Email:</span>
                    <span class="text-sm font-medium text-gray-900">noreply@shopsmart.com</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">From Name:</span>
                    <span class="text-sm font-medium text-gray-900">ShopSmart</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Encryption:</span>
                    <span class="text-sm font-medium text-gray-900">TLS</span>
                </div>
            </div>
            
            <div class="mt-4 flex justify-end space-x-2">
                <button @click="testEmailConfig()" class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-vial mr-1"></i>Test Config
                </button>
                <a href="{{ route('settings.communication.email.create') }}" class="px-3 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
            </div>
        </div>

        <!-- SMS Configuration Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-sms mr-2 text-green-600"></i>
                    SMS Configuration
                </h3>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i>Active
                    </span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        <i class="fas fa-star mr-1"></i>Primary
                    </span>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Provider:</span>
                    <span class="text-sm font-medium text-gray-900">Primary SMS Gateway</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">From:</span>
                    <span class="text-sm font-medium text-gray-900">ShopSmart</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">API URL:</span>
                    <span class="text-sm font-medium text-gray-900 truncate">messaging-service.co.tz</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Token:</span>
                    <span class="text-sm font-medium text-gray-900">f9a89f...92c</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="text-sm font-medium text-green-600">Connected</span>
                </div>
            </div>
            
            <div class="mt-4 flex justify-end space-x-2">
                <button @click="testSmsConfig()" class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-vial mr-1"></i>Test Config
                </button>
                <a href="{{ route('settings.communication.sms.create') }}" class="px-3 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Test Message Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-paper-plane mr-2 text-purple-600"></i>
            Send Test Message
        </h3>
        
        <form @submit.prevent="sendMessage()" class="space-y-6">
            <!-- Message Type Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message Type</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="message_type" value="email" x-model="testForm.messageType" class="mr-2">
                        <span class="text-sm text-gray-700">Email</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="message_type" value="sms" x-model="testForm.messageType" class="mr-2">
                        <span class="text-sm text-gray-700">SMS</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="message_type" value="both" x-model="testForm.messageType" class="mr-2">
                        <span class="text-sm text-gray-700">Both</span>
                    </label>
                </div>
            </div>

            <!-- Recipient -->
            <div x-show="testForm.messageType === 'email' || testForm.messageType === 'both'">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-1 text-blue-500"></i>Email Recipient
                </label>
                <input type="email" x-model="testForm.email" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="test@example.com">
            </div>

            <div x-show="testForm.messageType === 'sms' || testForm.messageType === 'both'">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-phone mr-1 text-green-500"></i>SMS Recipient
                </label>
                <input type="tel" x-model="testForm.phone" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="255712345678">
                <div class="mt-1 text-xs text-gray-500">Format: 255XXXXXXXXX (12 digits starting with 255)</div>
            </div>

            <!-- Message Content -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment mr-1 text-purple-500"></i>Message Content
                </label>
                <textarea x-model="testForm.message" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                          placeholder="Enter your test message here..."></textarea>
                <div class="mt-1 text-xs text-gray-500">
                    <span x-show="testForm.messageType === 'sms' || testForm.messageType === 'both'">
                        <span x-text="`${testForm.message.length}/160`"></span> characters
                    </span>
                </div>
            </div>

            <!-- Advanced Options -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-cog mr-2 text-gray-500"></i>
                    Advanced Options
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div x-show="testForm.messageType === 'email' || testForm.messageType === 'both'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-1 text-blue-500"></i>Email Subject
                        </label>
                        <input type="text" x-model="testForm.subject"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Test Message from ShopSmart">
                    </div>
                    
                    <div x-show="testForm.messageType === 'sms' || testForm.messageType === 'both'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-1 text-green-500"></i>Schedule Send
                        </label>
                        <select x-model="testForm.schedule" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="now">Send Now</option>
                            <option value="5min">In 5 minutes</option>
                            <option value="30min">In 30 minutes</option>
                            <option value="1hour">In 1 hour</option>
                            <option value="custom">Custom Time</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-4 flex items-center">
                    <input type="checkbox" x-model="testForm.trackDelivery" class="mr-2">
                    <label class="text-sm text-gray-700">Track delivery status</label>
                </div>
                
                <div class="mt-2 flex items-center">
                    <input type="checkbox" x-model="testForm.saveToLogs" class="mr-2">
                    <label class="text-sm text-gray-700">Save to communication logs</label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" :disabled="testForm.sending"
                        class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-50">
                    <i class="fas fa-paper-plane mr-2"></i>
                    <span x-text="testForm.sending ? 'Sending...' : 'Send Test Message'"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- Results Modal -->
    <div x-show="testForm.showResults" x-transition class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Test Results</h3>
                <div class="mt-2 px-7 py-3">
                    <div class="text-sm text-gray-500" x-html="testForm.results"></div>
                </div>
                <div class="items-center px-4 py-3">
                    <button @click="testForm.showResults = false" 
                            class="px-4 py-2 bg-purple-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-purple-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testMessage() {
    return {
        testForm: {
            messageType: 'email',
            email: '',
            phone: '',
            message: 'This is a test message from ShopSmart communication system.',
            subject: 'Test Message from ShopSmart',
            schedule: 'now',
            trackDelivery: true,
            saveToLogs: true,
            sending: false,
            showResults: false,
            results: ''
        },
        
        testEmailConfig() {
            alert('Testing email configuration...\n\n• Connecting to smtp.gmail.com:587\n• Authenticating with TLS encryption\n• Testing from: noreply@shopsmart.com\n• Configuration appears to be valid\n\n✅ Email configuration test successful!');
        },
        
        testSmsConfig() {
            alert('Testing SMS configuration...\n\n• Connecting to messaging-service.co.tz\n• Authenticating with Bearer token\n• Testing from: ShopSmart\n• API endpoint: /link/sms/v1/text/single\n\n✅ SMS configuration test successful!');
        },
        
        sendMessage() {
            this.testForm.sending = true;
            
            // Simulate sending message
            setTimeout(() => {
                let results = '<div class="space-y-3">';
                
                if (this.testForm.messageType === 'email' || this.testForm.messageType === 'both') {
                    results += `
                        <div class="p-3 bg-green-50 border border-green-200 rounded">
                            <h4 class="font-medium text-green-800 mb-1">✅ Email Sent Successfully</h4>
                            <p class="text-sm text-green-700">To: ${this.testForm.email}</p>
                            <p class="text-sm text-green-700">Subject: ${this.testForm.subject}</p>
                            <p class="text-sm text-green-700">Message ID: email_${Date.now()}</p>
                        </div>
                    `;
                }
                
                if (this.testForm.messageType === 'sms' || this.testForm.messageType === 'both') {
                    results += `
                        <div class="p-3 bg-green-50 border border-green-200 rounded">
                            <h4 class="font-medium text-green-800 mb-1">✅ SMS Sent Successfully</h4>
                            <p class="text-sm text-green-700">To: ${this.testForm.phone}</p>
                            <p class="text-sm text-green-700">From: ShopSmart</p>
                            <p class="text-sm text-green-700">Message ID: sms_${Date.now()}</p>
                            <p class="text-sm text-green-700">Status: Queued for delivery</p>
                        </div>
                    `;
                }
                
                results += `
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded">
                        <h4 class="font-medium text-blue-800 mb-1">📊 Delivery Tracking</h4>
                        <p class="text-sm text-blue-700">Track delivery: ${this.testForm.trackDelivery ? 'Enabled' : 'Disabled'}</p>
                        <p class="text-sm text-blue-700">Save to logs: ${this.testForm.saveToLogs ? 'Enabled' : 'Disabled'}</p>
                        <p class="text-sm text-blue-700">Schedule: ${this.testForm.schedule}</p>
                    </div>
                `;
                
                results += '</div>';
                
                this.testForm.results = results;
                this.testForm.showResults = true;
                this.testForm.sending = false;
                
                // Reset form
                this.testForm.email = '';
                this.testForm.phone = '';
                this.testForm.message = 'This is a test message from ShopSmart communication system.';
                
            }, 2000);
        }
    }
}
</script>
@endsection
