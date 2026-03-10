@extends('layouts.app')

@section('title', 'SMS Configuration')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">SMS Configuration</h1>
            <p class="text-gray-600 mt-1">Configure SMS settings for Primary SMS Gateway</p>
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
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-cog mr-2 text-green-600"></i>
            SMS Configuration
        </h3>
        
        <form x-data="smsConfigForm" @submit.prevent="submitForm()" class="space-y-6">
            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-1 text-blue-500"></i>Username
                </label>
                <input type="text" x-model="config.username" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="feedtan">
            </div>
            
            <!-- Sender ID -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-1 text-purple-500"></i>Sender ID
                </label>
                <input type="text" x-model="config.sender_id" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="ShopSmart">
            </div>
            
            <!-- API Token -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-key mr-1 text-yellow-500"></i>API Token
                </label>
                <div class="relative">
                    <input :type="showToken ? 'text' : 'password'" x-model="config.api_token" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="cedcce9becad866f59beac1fd5a235bc">
                    <button type="button" @click="showToken = !showToken" 
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700">
                        <i class="fas" :class="showToken ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <div class="mt-2 text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>Bearer token for API authentication
                </div>
            </div>
            
            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-1 text-red-500"></i>Password
                </label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" x-model="config.password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Your SMS password">
                    <button type="button" @click="showPassword = !showPassword" 
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700">
                        <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>
            
            <!-- Test Section -->
            <div class="border-t pt-6">
                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-vial mr-2 text-green-600"></i>
                    Test Configuration
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-1 text-green-500"></i>
                            Test Phone Number
                        </label>
                        <input type="tel" x-model="testPhone" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="255712345678">
                        <div class="mt-1 text-xs text-gray-500">Format: 255XXXXXXXXX</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment mr-1 text-green-500"></i>
                            Test Message
                        </label>
                        <input type="text" x-model="testMessage" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="This is a test SMS from ShopSmart">
                        <div class="mt-1 text-xs text-gray-500">
                            <span x-text="testMessage.length + '/160'"></span> characters
                        </div>
                    </div>
                </div>
                
                <!-- Test Buttons -->
                <div class="mt-4 flex gap-2">
                    <button type="button" @click="testSingleSms()" :disabled="testing"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50">
                        <i class="fas fa-paper-plane mr-2"></i>
                        <span x-show="!testing">Test Single SMS</span>
                        <span x-show="testing">Testing...</span>
                    </button>
                    
                    <button type="button" @click="testMultipleSms()" :disabled="testingMultiple"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                        <i class="fas fa-users mr-2"></i>
                        <span x-show="!testingMultiple">Test Multiple SMS</span>
                        <span x-show="testingMultiple">Testing...</span>
                    </button>
                </div>
                
                <!-- Test Status -->
                <div x-show="testStatus" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     :class="testSuccess ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'"
                     class="mt-4 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas mr-2" :class="testSuccess ? 'fa-check-circle text-green-600' : 'fa-exclamation-circle text-red-600'"></i>
                        <span x-text="testStatus"></span>
                    </div>
                </div>
                
                <!-- Test Results -->
                <div x-show="testResults.length > 0" class="mt-4">
                    <h5 class="text-sm font-semibold text-gray-900 mb-2">Test Results:</h5>
                    <div class="space-y-2">
                        <template x-for="result in testResults">
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900" x-text="'To: ' + result.to"></span>
                                        <span class="text-xs text-gray-500" x-text="'Message ID: ' + result.messageId"></span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-medium" :class="result.status === 'success' ? 'text-green-600' : 'text-red-600'" x-text="result.status.toUpperCase()"></span>
                                        <div class="text-xs text-gray-500" x-text="'Price: ' + result.price + ' TZS'"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Save Button (only show after successful test) -->
            <div x-show="testSuccess" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="border-t pt-6">
                <button type="submit" :disabled="saving"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50">
                    <i class="fas fa-save mr-2"></i>
                    <span x-show="!saving">Save Configuration</span>
                    <span x-show="saving">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SMS Test Modal -->
<div x-data="smsTestModal" x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;"
     @click.away="show = false">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-sms text-green-600 mr-2"></i>
                    Send Test SMS
                </h3>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Test Form -->
            <form @submit.prevent="sendTestSms()" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-1 text-green-500"></i>
                        Phone Numbers (comma separated)
                    </label>
                    <textarea x-model="phones" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                              placeholder="255712345678, 255716718040"></textarea>
                    <div class="mt-1 text-xs text-gray-500">Format: 255XXXXXXXXX (multiple numbers separated by comma)</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment mr-1 text-green-500"></i>
                        Message
                    </label>
                    <textarea x-model="message" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                              placeholder="Enter your test message here..."></textarea>
                    <div class="mt-1 text-xs text-gray-500">
                        <span x-text="message.length + '/160'"></span> characters
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="show = false" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" :disabled="sending"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50">
                        <i class="fas fa-paper-plane mr-2"></i>
                        <span x-show="!sending">Send SMS</span>
                        <span x-show="sending">Sending...</span>
                    </button>
                </div>
            </form>

            <!-- Status Message -->
            <div x-show="statusMessage" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 :class="statusType === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'"
                 class="mt-4 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas" :class="statusType === 'success' ? 'fa-check-circle text-green-600' : 'fa-exclamation-circle text-red-600'"></i>
                    <span x-text="statusMessage"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.smsConfigForm = {
    showToken: false,
    showPassword: false,
    testing: false,
    testingMultiple: false,
    saving: false,
    testSuccess: false,
    testStatus: '',
    testPhone: '255716718040',
    testMessage: 'This is a test SMS from ShopSmart communication system.',
    testResults: [],
    config: {
        username: 'feedtan',
        sender_id: 'ShopSmart',
        api_token: 'cedcce9becad866f59beac1fd5a235bc',
        password: ''
    },
    
    testSingleSms() {
        this.testing = true;
        this.testStatus = '';
        this.testSuccess = false;
        this.testResults = [];
        
        // Test single SMS using Messaging Service API
        fetch('https://messaging-service.co.tz/api/sms/v2/text/single', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + this.config.api_token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                from: this.config.sender_id,
                to: this.testPhone,
                text: this.testMessage
            })
        })
        .then(response => response.json())
        .then(data => {
            this.testing = false;
            if (data.messages && data.messages.length > 0) {
                this.testSuccess = true;
                this.testStatus = '✅ SMS configuration test successful! Message sent to ' + this.testPhone + '. You can now save configuration.';
                this.testResults = data.messages;
            } else {
                this.testSuccess = false;
                this.testStatus = '❌ SMS configuration test failed: ' + (data.message || 'Unknown error');
            }
        })
        .catch(error => {
            this.testing = false;
            this.testSuccess = false;
            this.testStatus = '❌ Network error during test: ' + error.message;
        });
    },
    
    testMultipleSms() {
        this.testingMultiple = true;
        this.testStatus = '';
        this.testSuccess = false;
        this.testResults = [];
        
        // Test multiple SMS using Messaging Service API
        const testPhones = ['255716718040', '255758483019'];
        const messages = testPhones.map(phone => ({
            from: this.config.sender_id,
            to: phone,
            text: this.testMessage + ' (Multiple test)'
        }));
        
        fetch('https://messaging-service.co.tz/api/sms/v2/text/multi', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + this.config.api_token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                messages: messages,
                flash: 0,
                reference: 'sms_config_test_' + Date.now()
            })
        })
        .then(response => response.json())
        .then(data => {
            this.testingMultiple = false;
            if (data.messages && data.messages.length > 0) {
                this.testSuccess = true;
                this.testStatus = '✅ Multiple SMS test successful! ' + data.messages.length + ' messages sent. You can now save configuration.';
                this.testResults = data.messages;
            } else {
                this.testSuccess = false;
                this.testStatus = '❌ Multiple SMS test failed: ' + (data.message || 'Unknown error');
            }
        })
        .catch(error => {
            this.testingMultiple = false;
            this.testSuccess = false;
            this.testStatus = '❌ Network error during test: ' + error.message;
        });
    },
    
    submitForm() {
        if (!this.testSuccess) {
            alert('Please test configuration first before saving.');
            return;
        }
        
        this.saving = true;
        
        // Submit form data
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/settings/communication/sms/store';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add configuration data
        Object.keys(this.config).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = this.config[key];
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
};

window.smsTestModal = {
    show: false,
    phones: '',
    message: 'This is a test SMS from ShopSmart.',
    sending: false,
    statusMessage: '',
    statusType: '',
    
    sendTestSms() {
        this.sending = true;
        
        const phoneNumbers = this.phones.split(',').map(phone => phone.trim()).filter(phone => phone);
        const messages = phoneNumbers.map(phone => ({
            from: 'ShopSmart',
            to: phone,
            text: this.message
        }));
        
        fetch('https://messaging-service.co.tz/api/sms/v2/text/multi', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer cedcce9becad866f59beac1fd5a235bc',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                messages: messages,
                flash: 0,
                reference: 'modal_test_' + Date.now()
            })
        })
        .then(response => response.json())
        .then(data => {
            this.sending = false;
            if (data.messages && data.messages.length > 0) {
                this.statusType = 'success';
                this.statusMessage = '✅ SMS sent successfully to ' + data.messages.length + ' recipient(s)!';
            } else {
                this.statusType = 'error';
                this.statusMessage = '❌ Failed to send SMS: ' + (data.message || 'Unknown error');
            }
        })
        .catch(error => {
            this.sending = false;
            this.statusType = 'error';
            this.statusMessage = '❌ Network error: ' + error.message;
        });
    }
};
</script>
@endsection
