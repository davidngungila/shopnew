@extends('layouts.app')

@section('title', isset($config) ? 'Edit SMS Configuration' : 'Create SMS Configuration')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ isset($config) ? 'Edit SMS Configuration' : 'Create SMS Configuration' }}</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Configure SMS settings for sending text messages</p>
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

    <form action="{{ isset($config) ? route('settings.communication.sms.update', $config->id) : route('settings.communication.sms.store') }}" method="POST">
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
                            placeholder="e.g., Primary SMS, Business SMS">
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

            <!-- SMS Settings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>SMS Configuration</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">API Secret / Auth Token <span class="text-red-500">*</span></label>
                        <input type="password" name="sms_api_secret" value="{{ old('sms_api_secret', $config->config['sms_api_secret'] ?? '') }}"
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="cedcce9becad866f59beac1fd5a235bc"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">From Number / Sender ID <span class="text-red-500">*</span></label>
                        <input type="text" name="sms_from" value="{{ old('sms_from', $config->config['sms_from'] ?? 'TANZANIATIP') }}"
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                            placeholder="TANZANIATIP"
                            required>
                    </div>
                </div>

                <!-- API Configuration Info -->
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">API Configuration (Hardcoded)</h3>
                    <div class="text-xs text-blue-700 space-y-1">
                        <p><strong>Provider:</strong> Messaging Service (messaging-service.co.tz)</p>
                        <p><strong>API URL:</strong> https://messaging-service.co.tz/api/sms/v2/text/single</p>
                        <p><strong>Method:</strong> POST</p>
                        <p><strong>Content-Type:</strong> application/json</p>
                        <p><strong>Authorization:</strong> Bearer [Your Token]</p>
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
                <div class="text-center">
                    <button type="button" onclick="window.smsTestModal.show = true; window.smsTestModal.reset();" 
                            class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] text-sm sm:text-base font-semibold transition-colors flex items-center justify-center space-x-2 mx-auto">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>Open SMS Test Modal</span>
                    </button>
                    <p class="text-xs text-gray-500 mt-2">Click to open the comprehensive SMS test modal with detailed progress tracking</p>
                </div>
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
<!-- Include the test-modals component which contains the SMS test modal -->
@include('components.test-modals')
@endpush
@endsection

