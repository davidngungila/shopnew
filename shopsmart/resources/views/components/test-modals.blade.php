<!-- Email Test Modal -->
<div x-data="emailTestModal" x-show="show" 
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
                    <i class="fas fa-envelope text-blue-600 mr-2"></i>
                    Test Email Configuration
                </h3>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Configuration Status -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">SMTP Host:</span>
                    <span class="text-sm text-gray-900">smtp.gmail.com</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">SMTP Port:</span>
                    <span class="text-sm text-gray-900">587</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">From Email:</span>
                    <span class="text-sm text-gray-900">noreply@shopsmart.com</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">From Name:</span>
                    <span class="text-sm text-gray-900">ShopSmart</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Encryption:</span>
                    <span class="text-sm text-gray-900">TLS</span>
                </div>
            </div>

            <!-- Test Form -->
            <form @submit.prevent="sendTestEmail()" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1 text-blue-500"></i>
                        Test Email Address
                    </label>
                    <input type="email" x-model="testEmail" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="test@example.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment mr-1 text-blue-500"></i>
                        Test Message
                    </label>
                    <textarea x-model="testMessage" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter your test message here..."></textarea>
                    <div class="mt-1 text-xs text-gray-500">
                        <span x-text="testMessage.length + '/160'"></span> characters
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1 text-blue-500"></i>
                        Subject (Optional)
                    </label>
                    <input type="text" x-model="testSubject"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Test Message from ShopSmart">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="show = false" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" :disabled="sending"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                        <i class="fas fa-paper-plane mr-2"></i>
                        <span x-show="!sending">Send Test Email</span>
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
                    Test SMS Configuration
                </h3>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Configuration Status -->
            <div class="mb-6 p-4 bg-green-50 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Provider:</span>
                    <span class="text-sm text-gray-900">Primary SMS Gateway</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">From:</span>
                    <span class="text-sm text-gray-900">ShopSmart</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">API URL:</span>
                    <span class="text-sm text-gray-900 truncate">messaging-service.co.tz</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Token:</span>
                    <span class="text-sm text-gray-900">f9a89f...92c</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <span class="text-sm font-medium text-green-600">Connected</span>
                </div>
            </div>

            <!-- Test Form -->
            <form @submit.prevent="sendTestSms()" class="space-y-4">
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
                    <textarea x-model="testMessage" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                              placeholder="Enter your test message here..."></textarea>
                    <div class="mt-1 text-xs text-gray-500">
                        <span x-text="testMessage.length + '/160'"></span> characters
                    </div>
                </div>

                <!-- Schedule Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1 text-green-500"></i>
                            Schedule Send
                        </label>
                        <select x-model="scheduleOption"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="now">Send Now</option>
                            <option value="5min">In 5 minutes</option>
                            <option value="30min">In 30 minutes</option>
                            <option value="1hour">In 1 hour</option>
                        </select>
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
                        <span x-show="!sending">Send Test SMS</span>
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

<!-- Modal Trigger Buttons -->
<script>
// Email Test Modal Data
window.emailTestModal = {
    show: false,
    testEmail: '',
    testMessage: 'This is a test email from ShopSmart communication system.',
    testSubject: 'Test Message from ShopSmart',
    sending: false,
    statusMessage: '',
    statusType: '',
    
    sendTestEmail() {
        this.sending = true;
        
        // Simulate sending email
        fetch('/api/sms/send', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer f9a89f439206e27169ead766463ca92c',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                to: this.testEmail,
                message: this.testMessage,
                subject: this.testSubject,
                reference: 'email_test_' + Date.now()
            })
        })
        .then(response => response.json())
        .then(data => {
            this.sending = false;
            if (data.success) {
                this.statusType = 'success';
                this.statusMessage = 'Email sent successfully to ' + this.testEmail + '!';
            } else {
                this.statusType = 'error';
                this.statusMessage = 'Failed to send email: ' + (data.message || 'Unknown error');
            }
        })
        .catch(error => {
            this.sending = false;
            this.statusType = 'error';
            this.statusMessage = 'Network error: ' + error.message;
        });
    },
    
    reset() {
        this.testEmail = '';
        this.testMessage = 'This is a test email from ShopSmart communication system.';
        this.testSubject = 'Test Message from ShopSmart';
        this.sending = false;
        this.statusMessage = '';
        this.statusType = '';
    }
};

// SMS Test Modal Data
window.smsTestModal = {
    show: false,
    testPhone: '',
    testMessage: 'This is a test SMS from ShopSmart communication system.',
    scheduleOption: 'now',
    sending: false,
    statusMessage: '',
    statusType: '',
    
    sendTestSms() {
        this.sending = true;
        
        // Use the actual SMS test endpoint
        fetch('{{ route("settings.communication.test-sms") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                phone: this.testPhone,
                message: this.testMessage,
                config_id: {{ isset($config) && $config ? $config->id : 'null' }}
            })
        })
        .then(response => response.json())
        .then(data => {
            this.sending = false;
            if (data.success) {
                this.statusType = 'success';
                this.statusMessage = '✅ Test SMS sent successfully to ' + this.testPhone + '!';
                if (data.details) {
                    this.statusMessage += '\n📋 Reference: ' + (data.details.reference_id || 'N/A');
                }
            } else {
                this.statusType = 'error';
                this.statusMessage = '❌ Failed to send SMS: ' + (data.message || 'Unknown error');
            }
        })
        .catch(error => {
            this.sending = false;
            this.statusType = 'error';
            this.statusMessage = 'Network error: ' + error.message;
        });
    },
    
    reset() {
        this.testPhone = '';
        this.testMessage = 'This is a test SMS from ShopSmart communication system.';
        this.scheduleOption = 'now';
        this.sending = false;
        this.statusMessage = '';
        this.statusType = '';
    }
};
</script>
