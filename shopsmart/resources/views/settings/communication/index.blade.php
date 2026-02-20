@extends('layouts.app')

@section('title', 'Communication Configurations')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Communication Configurations</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Manage all email and SMS configurations</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('settings.communication.email.create') }}" class="px-3 sm:px-4 py-2 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] flex items-center space-x-2 text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Email Config</span>
            </a>
            <a href="{{ route('settings.communication.sms.create') }}" class="px-3 sm:px-4 py-2 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] flex items-center space-x-2 text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New SMS Config</span>
            </a>
            <a href="{{ route('settings.index') }}" class="px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Back to Settings</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>
    </div>

    <!-- Email Configurations -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
            <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <span>Email Configurations</span>
        </h2>
        
        @if($emailConfigs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From Address</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Primary</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($emailConfigs as $config)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $config->name }}
                                @if($config->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ $config->description }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $config->config['mail_from_address'] ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ strtoupper($config->config['mail_mailer'] ?? 'smtp') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($config->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                @if($config->is_primary)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#009245] text-white">
                                        Primary
                                    </span>
                                @else
                                    <form action="{{ route('settings.communication.set-primary', $config->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-[#009245] hover:text-[#007a38] font-medium" onclick="return confirm('Set this as primary configuration?')">
                                            Set Primary
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('settings.communication.email.edit', $config->id) }}" class="text-blue-600 hover:text-blue-700" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('settings.communication.destroy', $config->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this configuration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm">No email configurations found</p>
                <a href="{{ route('settings.communication.email.create') }}" class="mt-4 inline-block px-4 py-2 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] text-sm font-semibold">
                    Create Email Configuration
                </a>
            </div>
        @endif
    </div>

    <!-- SMS Configurations -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
            <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <span>SMS Configurations</span>
        </h2>
        
        @if($smsConfigs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From Number</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Primary</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($smsConfigs as $config)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $config->name }}
                                @if($config->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ $config->description }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ ucfirst($config->config['sms_provider'] ?? 'custom') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $config->config['sms_from'] ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($config->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                @if($config->is_primary)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#009245] text-white">
                                        Primary
                                    </span>
                                @else
                                    <form action="{{ route('settings.communication.set-primary', $config->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-[#009245] hover:text-[#007a38] font-medium" onclick="return confirm('Set this as primary configuration?')">
                                            Set Primary
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('settings.communication.sms.edit', $config->id) }}" class="text-blue-600 hover:text-blue-700" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('settings.communication.destroy', $config->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this configuration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-sm">No SMS configurations found</p>
                <a href="{{ route('settings.communication.sms.create') }}" class="mt-4 inline-block px-4 py-2 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] text-sm font-semibold">
                    Create SMS Configuration
                </a>
            </div>
        @endif
    </div>
</div>
@endsection







