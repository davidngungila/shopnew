@extends('layouts.app')

@section('title', isset($config) ? 'Edit Email Configuration' : 'Create Email Configuration')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ isset($config) ? 'Edit Email Configuration' : 'Create Email Configuration' }}</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Configure email settings for sending notifications</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('settings.communication.index') }}" class="px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Back to List</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>
    </div>

    <form action="{{ isset($config) ? route('settings.communication.email.update', $config->id) : route('settings.communication.email.store') }}" method="POST">
        @csrf
        @if(isset($config))
            @method('PUT')
        @endif
        
        <div class="space-y-4 sm:space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Basic Information</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Configuration Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $config->name ?? '') }}" required
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="e.g., Primary SMTP, Gmail Account">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Status <span class="text-gray-400">(Optional)</span></label>
                        <select name="is_active" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <option value="1" {{ old('is_active', $config->is_active ?? true) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $config->is_active ?? true) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Description <span class="text-gray-400">(Optional)</span></label>
                        <textarea name="description" rows="2" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="Brief description of this configuration">{{ old('description', $config->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Email Settings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>Email Configuration</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Mail Driver <span class="text-gray-400">(Optional)</span></label>
                        <select name="mail_mailer" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <option value="smtp" {{ old('mail_mailer', $config->config['mail_mailer'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ old('mail_mailer', $config->config['mail_mailer'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="mailgun" {{ old('mail_mailer', $config->config['mail_mailer'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ old('mail_mailer', $config->config['mail_mailer'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            <option value="postmark" {{ old('mail_mailer', $config->config['mail_mailer'] ?? '') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                            <option value="resend" {{ old('mail_mailer', $config->config['mail_mailer'] ?? '') == 'resend' ? 'selected' : '' }}>Resend</option>
                            <option value="log" {{ old('mail_mailer', $config->config['mail_mailer'] ?? '') == 'log' ? 'selected' : '' }}>Log (Testing)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">From Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $config->config['mail_from_address'] ?? config('mail.from.address')) }}" required
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="noreply@example.com">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">From Name <span class="text-gray-400">(Optional)</span></label>
                        <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $config->config['mail_from_name'] ?? config('mail.from.name')) }}"
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="ShopSmart">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Host <span class="text-gray-400">(Optional)</span></label>
                        <input type="text" name="mail_host" value="{{ old('mail_host', $config->config['mail_host'] ?? config('mail.mailers.smtp.host')) }}"
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="smtp.mailtrap.io">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Port <span class="text-gray-400">(Optional)</span></label>
                        <input type="number" name="mail_port" value="{{ old('mail_port', $config->config['mail_port'] ?? config('mail.mailers.smtp.port')) }}"
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="2525">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Encryption <span class="text-gray-400">(Optional)</span></label>
                        <select name="mail_encryption" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <option value="tls" {{ old('mail_encryption', $config->config['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('mail_encryption', $config->config['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="" {{ old('mail_encryption', $config->config['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Username <span class="text-gray-400">(Optional)</span></label>
                        <input type="text" name="mail_username" value="{{ old('mail_username', $config->config['mail_username'] ?? '') }}"
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="your-username">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Password <span class="text-gray-400">(Optional)</span></label>
                        <input type="password" name="mail_password" value="{{ old('mail_password', $config->config['mail_password'] ?? '') }}"
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="{{ isset($config) && $config->config['mail_password'] ? '•••••••• (leave blank to keep current)' : '••••••••' }}">
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
                            onclick="testEmail()"
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
            <div class="flex justify-end gap-3">
                <a href="{{ route('settings.communication.index') }}" class="px-4 sm:px-6 py-2 sm:py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm sm:text-base transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 sm:px-6 py-2 sm:py-3 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] font-semibold text-sm sm:text-base transition-colors">
                    {{ isset($config) ? 'Update Configuration' : 'Create Configuration' }}
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function testEmail() {
        const emailInput = document.getElementById('test_email');
        const email = emailInput ? emailInput.value : '';
        const statusEl = document.getElementById('test_email_status');
        const button = document.getElementById('test_email_button');

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
            body: JSON.stringify({ email: email, config_id: {{ isset($config) && $config ? $config->id : 'null' }} })
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
</script>
@endpush
@endsection

