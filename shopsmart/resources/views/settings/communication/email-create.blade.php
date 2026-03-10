@extends('layouts.app')

@section('title', 'Email Configuration')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Email Configuration</h1>
            <p class="text-gray-600 mt-1">Configure email settings for Primary Email Gateway</p>
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
                    <span class="text-gray-900 font-medium">Primary Email Gateway</span>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-info-circle mr-1 text-gray-500"></i>Description
                </label>
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                    <span class="text-gray-900">Primary email gateway provider configured from system settings</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Configuration -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-cog mr-2 text-green-600"></i>
            Email Configuration
        </h3>
        
        <form x-data="emailConfigForm" @submit.prevent="submitForm()" class="space-y-6">
            <!-- SMTP Host -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-server mr-1 text-purple-500"></i>SMTP Host
                </label>
                <input type="text" x-model="config.smtp_host" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="smtp.gmail.com">
            </div>
            
            <!-- SMTP Port -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-network-wired mr-1 text-orange-500"></i>SMTP Port
                </label>
                <input type="number" x-model="config.smtp_port" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="587">
            </div>
            
            <!-- SMTP Username -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-1 text-blue-500"></i>SMTP Username
                </label>
                <input type="text" x-model="config.smtp_username" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="your-email@gmail.com">
            </div>
            
            <!-- SMTP Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-1 text-red-500"></i>SMTP Password (App Password)
                </label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" x-model="config.smtp_password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Use Gmail App Password (not regular password)">
                    <button type="button" @click="showPassword = !showPassword" 
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700">
                        <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <div class="mt-2 text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>Use Gmail App Password, not regular password. <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-blue-600 hover:text-blue-800">Get App Password</a>
                </div>
            </div>
            
            <!-- SMTP Encryption -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-shield-alt mr-1 text-green-500"></i>SMTP Encryption
                </label>
                <select x-model="config.smtp_encryption" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="TLS">TLS</option>
                    <option value="SSL">SSL</option>
                    <option value="none">None</option>
                </select>
            </div>
            
            <!-- From Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-1 text-indigo-500"></i>From Email
                </label>
                <input type="email" x-model="config.from_email" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="noreply@shopsmart.com">
            </div>
            
            <!-- From Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-tag mr-1 text-yellow-500"></i>From Name
                </label>
                <input type="text" x-model="config.from_name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="ShopSmart">
            </div>
            
            <!-- Test Section -->
            <div class="border-t pt-6">
                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-vial mr-2 text-blue-600"></i>
                    Test Configuration
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-1 text-blue-500"></i>
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
                        <input type="text" x-model="testMessage" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="This is a test email from ShopSmart">
                    </div>
                </div>
                
                <!-- Test Button -->
                <div class="mt-4">
                    <button type="button" @click="testConfiguration()" :disabled="testing"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                        <i class="fas fa-vial mr-2"></i>
                        <span x-show="!testing">Test Configuration</span>
                        <span x-show="testing">Testing...</span>
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

<script>
window.emailConfigForm = {
    showPassword: false,
    testing: false,
    saving: false,
    testSuccess: false,
    testStatus: '',
    testEmail: '',
    testMessage: 'This is a test email from ShopSmart communication system.',
    config: {
        smtp_host: 'smtp.gmail.com',
        smtp_port: '587',
        smtp_username: 'noreply@shopsmart.com',
        smtp_password: '',
        smtp_encryption: 'TLS',
        from_email: 'noreply@shopsmart.com',
        from_name: 'ShopSmart'
    },
    
    testConfiguration() {
        this.testing = true;
        this.testStatus = '';
        this.testSuccess = false;
        
        // Test email configuration using proper form submission
        const formData = new FormData();
        formData.append('test_email', this.testEmail);
        formData.append('test_message', this.testMessage);
        formData.append('test_subject', 'Test Email from ShopSmart');
        formData.append('smtp_host', this.config.smtp_host);
        formData.append('smtp_port', this.config.smtp_port);
        formData.append('smtp_username', this.config.smtp_username);
        formData.append('smtp_password', this.config.smtp_password);
        formData.append('smtp_encryption', this.config.smtp_encryption);
        formData.append('from_email', this.config.from_email);
        formData.append('from_name', this.config.from_name);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('/settings/communication/email/test', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            this.testing = false;
            if (data.success) {
                this.testSuccess = true;
                this.testStatus = '✅ Email configuration test successful! Message sent to ' + this.testEmail + '. You can now save configuration.';
            } else {
                this.testSuccess = false;
                this.testStatus = '❌ Email configuration test failed: ' + (data.message || 'Unknown error');
            }
        })
        .catch(error => {
            this.testing = false;
            this.testSuccess = false;
            this.testStatus = '❌ Network error during test: ' + error.message;
        });
    },
    
    submitForm() {
        if (!this.testSuccess) {
            alert('Please test the configuration first before saving.');
            return;
        }
        
        this.saving = true;
        
        // Submit form data
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/settings/communication/email/store';
        
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
</script>
@endsection
