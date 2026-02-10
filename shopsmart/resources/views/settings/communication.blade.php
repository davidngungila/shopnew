@extends('layouts.app')

@section('title', 'Communication Settings')

@section('content')
<div class="space-y-4 sm:space-y-6" x-data="{ activeTab: 'email' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Communication Settings</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Configure email and SMS communication settings</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('settings.index') }}" class="px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Back to Settings</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto">
                <button @click="activeTab = 'email'" 
                    :class="activeTab === 'email' ? 'border-[#009245] text-[#009245]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>Email Configuration</span>
                </button>
                <button @click="activeTab = 'sms'" 
                    :class="activeTab === 'sms' ? 'border-[#009245] text-[#009245]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>SMS Configuration</span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="mt-4 sm:mt-6">
        <!-- Email Configuration Tab -->
        <div x-show="activeTab === 'email'" x-transition>
            <form action="{{ route('settings.communication.update') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="email">
                
                <div class="space-y-4 sm:space-y-6">
                    <!-- Email Settings -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-4">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>Email Configuration</span>
                            </h2>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_enabled" value="1" {{ ($settings['email_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#009245] rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#009245]"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Enable Email</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Mail Driver <span class="text-gray-400">(Optional)</span></label>
                                <select name="mail_mailer" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                                    <option value="smtp" {{ ($settings['mail_mailer'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ ($settings['mail_mailer'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ ($settings['mail_mailer'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="ses" {{ ($settings['mail_mailer'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                    <option value="postmark" {{ ($settings['mail_mailer'] ?? '') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                                    <option value="resend" {{ ($settings['mail_mailer'] ?? '') == 'resend' ? 'selected' : '' }}>Resend</option>
                                    <option value="log" {{ ($settings['mail_mailer'] ?? '') == 'log' ? 'selected' : '' }}>Log (Testing)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">From Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? config('mail.from.address') }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="noreply@example.com" required>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">From Name <span class="text-gray-400">(Optional)</span></label>
                                <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? config('mail.from.name') }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="ShopSmart">
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Host <span class="text-gray-400">(Optional)</span></label>
                                <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? config('mail.mailers.smtp.host') }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="smtp.mailtrap.io">
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Port <span class="text-gray-400">(Optional)</span></label>
                                <input type="number" name="mail_port" value="{{ $settings['mail_port'] ?? config('mail.mailers.smtp.port') }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="2525">
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Encryption <span class="text-gray-400">(Optional)</span></label>
                                <select name="mail_encryption" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                                    <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="" {{ ($settings['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Username <span class="text-gray-400">(Optional)</span></label>
                                <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="your-username">
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Password <span class="text-gray-400">(Optional)</span></label>
                                <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <!-- Test Email Section -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Test Email Configuration</span>
                        </h2>
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <div class="flex-1">
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Test Email Address</label>
                                <input type="email" id="test_email" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="test@example.com">
                            </div>
                            <div class="flex items-end">
                                <button
                                    id="test_email_button"
                                    type="button"
                                    onclick="testEmail(event)"
                                    class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] text-sm sm:text-base font-semibold transition-colors flex items-center justify-center space-x-2"
                                >
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Send Test Email</span>
                                </button>
                            </div>
                        </div>
                        <p id="test_email_status" class="mt-2 text-xs text-gray-500"></p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 sm:px-6 py-2 sm:py-3 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] font-semibold text-sm sm:text-base transition-colors">
                            Save Email Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- SMS Configuration Tab -->
        <div x-show="activeTab === 'sms'" x-transition>
            <form action="{{ route('settings.communication.update') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="sms">
                
                <div class="space-y-4 sm:space-y-6">
                    <!-- SMS Settings -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-4">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>SMS Configuration</span>
                            </h2>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="sms_enabled" value="1" {{ ($settings['sms_enabled'] ?? '0') == '1' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#009245] rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#009245]"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Enable SMS</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMS Provider <span class="text-gray-400">(Optional)</span></label>
                                <select name="sms_provider" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                                    <option value="twilio" {{ ($settings['sms_provider'] ?? 'twilio') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                    <option value="nexmo" {{ ($settings['sms_provider'] ?? '') == 'nexmo' ? 'selected' : '' }}>Vonage (Nexmo)</option>
                                    <option value="aws_sns" {{ ($settings['sms_provider'] ?? '') == 'aws_sns' ? 'selected' : '' }}>AWS SNS</option>
                                    <option value="messagebird" {{ ($settings['sms_provider'] ?? '') == 'messagebird' ? 'selected' : '' }}>MessageBird</option>
                                    <option value="plivo" {{ ($settings['sms_provider'] ?? '') == 'plivo' ? 'selected' : '' }}>Plivo</option>
                                    <option value="custom" {{ ($settings['sms_provider'] ?? '') == 'custom' ? 'selected' : '' }}>Custom API</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">API Key / Account SID <span class="text-gray-400">(Optional)</span></label>
                                <input type="text" name="sms_api_key" value="{{ $settings['sms_api_key'] ?? '' }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">API Secret / Auth Token <span class="text-gray-400">(Optional)</span></label>
                                <input type="password" name="sms_api_secret" value="{{ $settings['sms_api_secret'] ?? '' }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="••••••••">
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">From Number / Sender ID <span class="text-gray-400">(Optional)</span></label>
                                <input type="text" name="sms_from" value="{{ $settings['sms_from'] ?? '' }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="+1234567890 or SenderID">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">API URL (Custom Provider Only) <span class="text-gray-400">(Optional)</span></label>
                                <input type="url" name="sms_api_url" value="{{ $settings['sms_api_url'] ?? '' }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="https://api.example.com/send">
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Region (Optional)</label>
                                <input type="text" name="sms_region" value="{{ $settings['sms_region'] ?? '' }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="us-east-1">
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Default Country Code <span class="text-gray-400">(Optional)</span></label>
                                <input type="text" name="sms_country_code" value="{{ $settings['sms_country_code'] ?? '+1' }}" 
                                    class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="+1">
                            </div>
                        </div>
                    </div>

                    <!-- Test SMS Section -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Test SMS Configuration</span>
                        </h2>
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <div class="flex-1">
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Test Phone Number</label>
                                <input type="tel" id="test_phone" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" 
                                    placeholder="+1234567890">
                            </div>
                            <div class="flex items-end">
                                <button type="button" onclick="testSMS()" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] text-sm sm:text-base font-semibold transition-colors flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span>Send Test SMS</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 sm:px-6 py-2 sm:py-3 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] font-semibold text-sm sm:text-base transition-colors">
                            Save SMS Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function testEmail(e) {
        if (e && typeof e.preventDefault === 'function') {
            e.preventDefault();
        }

        const emailInput = document.getElementById('test_email');
        const email = emailInput ? emailInput.value : '';
        const statusEl = document.getElementById('test_email_status');
        const button = (e && e.target) ? e.target.closest('button') : document.getElementById('test_email_button');

        if (!email) {
            alert('Please enter a test email address');
            return;
        }

        const originalText = button ? button.innerHTML : '';
        if (button) {
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin h-4 w-4 sm:h-5 sm:w-5 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Sending...';
        }

        if (statusEl) {
            statusEl.textContent = 'Sending test email...';
            statusEl.className = 'mt-2 text-xs text-gray-500';
        }

        fetch('{{ route("settings.communication.test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (statusEl) {
                    statusEl.textContent = 'Test email sent successfully!';
                    statusEl.className = 'mt-2 text-xs text-green-600';
                } else {
                    alert('Test email sent successfully!');
                }
            } else {
                const message = 'Error sending test email: ' + (data.message || 'Unknown error');
                if (statusEl) {
                    statusEl.textContent = message;
                    statusEl.className = 'mt-2 text-xs text-red-600';
                } else {
                    alert(message);
                }
            }
        })
        .catch(error => {
            const message = 'Error sending test email: ' + error.message;
            if (statusEl) {
                statusEl.textContent = message;
                statusEl.className = 'mt-2 text-xs text-red-600';
            } else {
                alert(message);
            }
        })
        .finally(() => {
            if (button) {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });
    }

    function testSMS() {
        const phone = document.getElementById('test_phone').value;
        if (!phone) {
            alert('Please enter a test phone number');
            return;
        }

        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-4 w-4 sm:h-5 sm:w-5 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Sending...';

        fetch('{{ route("settings.communication.test-sms") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Test SMS sent successfully!');
            } else {
                alert('Error sending test SMS: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Error sending test SMS: ' + error.message);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
</script>
@endpush
@endsection
