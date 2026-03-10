@extends('layouts.app')

@section('title', 'SMS Provider Configuration')

@section('content')
<div class="space-y-6" x-data="smsProviderConfig()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">SMS Provider Details</h1>
            <p class="text-gray-600 mt-1">Primary SMS Gateway configuration and testing</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('settings.communication') }}" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors" style="background-color: #6b7280;">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <button @click="editProvider()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </button>
        </div>
    </div>

    <!-- Status Messages -->
    <div x-show="notification.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         :class="notification.type === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'"
         class="rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i :class="notification.type === 'success' ? 'fas fa-check-circle text-green-600' : 'fas fa-exclamation-circle text-red-600'" class="mr-3"></i>
            <p :class="notification.type === 'success' ? 'text-green-800' : 'text-red-800'" x-text="notification.message"></p>
        </div>
    </div>

    <!-- Provider Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-server mr-2 text-blue-600"></i>
                Provider Information
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
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-1 text-gray-500"></i>Provider Name
                </label>
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                    <span class="text-gray-900 font-medium">Primary SMS Gateway</span>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-info-circle mr-1 text-gray-500"></i>Description
                </label>
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                    <span class="text-gray-900">Primary SMS gateway provider configured from system settings</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SMS Configuration -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-cog mr-2 text-green-600"></i>
            SMS Configuration
        </h3>
        
        <div class="space-y-6">
            <!-- Bearer Token (API Key) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-key mr-1 text-yellow-500"></i>Bearer Token (API Key)
                </label>
                <div class="relative">
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                        <div class="flex items-center justify-between">
                            <code class="text-sm text-gray-900 font-mono">f9a89f439206e27169ead766463ca92c</code>
                            <button @click="toggleTokenVisibility()" class="text-gray-500 hover:text-gray-700">
                                <i :class="tokenVisible ? 'fas fa-eye-slash' : 'fas fa-eye'" class="ml-2"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>Length: 32 characters | Stored in username field
                    </div>
                </div>
            </div>
            
            <!-- SMS Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-1 text-red-500"></i>SMS Password
                </label>
                <div class="relative">
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-900">••••••••</span>
                            <button @click="togglePasswordVisibility()" class="text-gray-500 hover:text-gray-700">
                                <i :class="passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'" class="ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SMS From (Sender Name) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-1 text-blue-500"></i>SMS From (Sender Name)
                </label>
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                    <span class="text-gray-900 font-medium">FEEDTAN</span>
                </div>
            </div>
            
            <!-- SMS API URL -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-link mr-1 text-purple-500"></i>SMS API URL
                </label>
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                    <code class="text-sm text-gray-900 font-mono break-all">https://messaging-service.co.tz/link/sms/v1/text/single</code>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Configuration -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-vial mr-2 text-orange-600"></i>
            Test Configuration
        </h3>
        
        <p class="text-gray-600 mb-6">Send a test SMS to verify this provider configuration is working correctly.</p>
        
        <form @submit.prevent="sendTestSms()" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-phone mr-1 text-green-500"></i>Test Phone Number <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="tel" 
                           x-model="testForm.phone" 
                           placeholder="255712345678"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           :class="errors.phone ? 'border-red-500' : ''">
                    <div class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>Format: 255XXXXXXXXX (12 digits starting with 255)
                    </div>
                    <div x-show="errors.phone" class="mt-1 text-sm text-red-600" x-text="errors.phone"></div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment mr-1 text-blue-500"></i>Test Message
                </label>
                <div class="relative">
                    <textarea x-model="testForm.message" 
                              rows="3" 
                              placeholder="Test SMS from Primary SMS Gateway - Configuration test successful!"
                              maxlength="160"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              :class="errors.message ? 'border-red-500' : ''"></textarea>
                    <div class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>Max 160 characters
                        <span class="float-right" x-text="`${testForm.message.length}/160`"></span>
                    </div>
                    <div x-show="errors.message" class="mt-1 text-sm text-red-600" x-text="errors.message"></div>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div x-show="testStatus.sending" class="flex items-center text-blue-600">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Sending test SMS...</span>
                </div>
                
                <button type="submit" 
                        :disabled="testStatus.sending || !testForm.phone"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                    <i class="fas fa-paper-plane mr-2"></i>
                    <span x-text="testStatus.sending ? 'Sending...' : 'Send Test SMS'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function smsProviderConfig() {
    return {
        tokenVisible: false,
        passwordVisible: false,
        testForm: {
            phone: '255712345678',
            message: 'Test SMS from Primary SMS Gateway - Configuration test successful!'
        },
        errors: {},
        testStatus: {
            sending: false
        },
        notification: {
            show: false,
            type: 'success',
            message: ''
        },
        
        config: {
            name: 'Primary SMS Gateway',
            description: 'Primary SMS gateway provider configured from system settings',
            status: 'active',
            is_primary: true,
            bearer_token: 'f9a89f439206e27169ead766463ca92c',
            password: '',
            from: 'FEEDTAN',
            api_url: 'https://messaging-service.co.tz/link/sms/v1/text/single'
        },
        
        toggleTokenVisibility() {
            this.tokenVisible = !this.tokenVisible;
        },
        
        togglePasswordVisibility() {
            this.passwordVisible = !this.passwordVisible;
        },
        
        validateForm() {
            this.errors = {};
            
            // Validate phone number
            if (!this.testForm.phone) {
                this.errors.phone = 'Phone number is required';
            } else if (!/^255[0-9]{9}$/.test(this.testForm.phone.replace(/\D/g, ''))) {
                this.errors.phone = 'Invalid phone number format. Expected: 255XXXXXXXXX (12 digits starting with 255)';
            }
            
            // Validate message
            if (!this.testForm.message) {
                this.errors.message = 'Test message is required';
            } else if (this.testForm.message.length > 160) {
                this.errors.message = 'Message must be 160 characters or less';
            }
            
            return Object.keys(this.errors).length === 0;
        },
        
        async sendTestSms() {
            if (!this.validateForm()) {
                return;
            }
            
            this.testStatus.sending = true;
            
            try {
                // Format phone number
                const cleanedPhone = this.testForm.phone.replace(/\D/g, '');
                
                // Prepare API request
                const response = await fetch('/settings/communication/test-sms', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        phone: cleanedPhone,
                        message: this.testForm.message,
                        provider: this.config
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showNotification('success', 'Test SMS sent successfully! Check your phone.');
                    this.testForm.message = 'Test SMS from Primary SMS Gateway - Configuration test successful!';
                } else {
                    this.showNotification('error', result.error || 'Failed to send test SMS');
                }
                
            } catch (error) {
                console.error('SMS test error:', error);
                this.showNotification('error', 'Network error. Please try again.');
            } finally {
                this.testStatus.sending = false;
            }
        },
        
        showNotification(type, message) {
            this.notification = {
                show: true,
                type: type,
                message: message
            };
            
            setTimeout(() => {
                this.notification.show = false;
            }, 5000);
        },
        
        editProvider() {
            // Redirect to edit page
            window.location.href = '/settings/communication/sms/edit';
        }
    }
}
</script>
@endsection
