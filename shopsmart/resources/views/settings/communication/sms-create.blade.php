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
            <a href="{{ route('settings.communication') }}" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors" style="background-color: #6b7280;">
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
</div>
@endsection
