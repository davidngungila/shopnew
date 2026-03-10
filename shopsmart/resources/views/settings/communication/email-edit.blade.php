@extends('layouts.app')

@section('title', 'Edit Email Configuration')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Edit Email Configuration</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Update your email settings for sending notifications</p>
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

    <form action="{{ route('settings.communication.email.update', $config->id) }}" method="POST">
        @csrf
        @method('PUT')
        
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
                        <input type="text" name="name" value="{{ old('name', $config->name) }}" required
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="e.g., Primary SMTP, Gmail Account">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Status <span class="text-gray-400">(Optional)</span></label>
                        <select name="is_active" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <option value="1" {{ old('is_active', $config->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $config->is_active) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Description <span class="text-gray-400">(Optional)</span></label>
                        <textarea name="description" rows="2" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="Brief description of this configuration">{{ old('description', $config->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- SMTP Settings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>SMTP Configuration</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Host <span class="text-red-500">*</span></label>
                        <input type="text" name="mail_host" value="{{ old('mail_host', $config->config['mail_host'] ?? '') }}" required
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="smtp.gmail.com">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Port <span class="text-red-500">*</span></label>
                        <input type="number" name="mail_port" value="{{ old('mail_port', $config->config['mail_port'] ?? 587) }}" required
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="587">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Username <span class="text-red-500">*</span></label>
                        <input type="email" name="mail_username" value="{{ old('mail_username', $config->config['mail_username'] ?? '') }}" required
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="your-email@gmail.com">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">SMTP Password <span class="text-red-500">*</span></label>
                        <input type="password" name="mail_password" value="{{ old('mail_password', $config->config['mail_password'] ?? '') }}" required
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="{{ isset($config->config['mail_password']) && $config->config['mail_password'] ? '•••••••• (leave blank to keep current)' : '••••••••' }}">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Encryption <span class="text-red-500">*</span></label>
                        <select name="mail_encryption" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" required>
                            <option value="TLS" {{ old('mail_encryption', $config->config['mail_encryption'] ?? 'TLS') == 'TLS' ? 'selected' : '' }}>TLS</option>
                            <option value="SSL" {{ old('mail_encryption', $config->config['mail_encryption'] ?? 'TLS') == 'SSL' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ old('mail_encryption', $config->config['mail_encryption'] ?? 'TLS') == 'none' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">From Email <span class="text-red-500">*</span></label>
                        <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $config->config['mail_from_address'] ?? '') }}" required
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="noreply@shopsmart.com">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">From Name <span class="text-red-500">*</span></label>
                        <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $config->config['mail_from_name'] ?? '') }}" required
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="ShopSmart">
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
                <div class="text-center">
                    <button type="button" onclick="window.emailTestModal.show = true; window.emailTestModal.reset();" 
                            class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] text-sm sm:text-base font-semibold transition-colors flex items-center justify-center space-x-2 mx-auto">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Open Email Test Modal</span>
                    </button>
                    <p class="text-xs text-gray-500 mt-2">Click to open the comprehensive email test modal with detailed progress tracking</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('settings.communication.index') }}" class="px-4 sm:px-6 py-2 sm:py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-sm sm:text-base transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 sm:px-6 py-2 sm:py-3 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] font-semibold text-sm sm:text-base transition-colors">
                    Update Configuration
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<!-- Include the test-modals component which contains the email test modal -->
@endsection
@endsection
