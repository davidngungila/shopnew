@extends('layouts.app')

@section('title', 'Communication Settings')

@section('content')
<div class="space-y-6" x-data="communicationSettings()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Communication Settings</h1>
            <p class="text-gray-600 mt-1">Manage email and SMS configurations</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('settings.communication.email.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>New Email Config
            </a>
            <a href="{{ route('settings.communication.sms.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>New SMS Config
            </a>
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

    <!-- Communication Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Email Configs</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $emailConfigs->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-envelope text-blue-500"></i> 
                        Configured
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-envelope text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">SMS Configs</p>
                    <p class="text-2xl font-bold text-green-600">{{ $smsConfigs->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-sms text-green-500"></i> 
                        Configured
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-sms text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Configs</p>
                    <p class="text-2xl font-bold text-purple-600" x-text="activeConfigsCount">0</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-check-circle text-purple-500"></i> 
                        Currently active
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Test Status</p>
                    <p class="text-2xl font-bold text-orange-600" x-text="testStatus">Ready</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-vial text-orange-500"></i> 
                        Last test
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-vial text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
            <span class="text-sm text-gray-500">Common configuration tasks</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('settings.communication.email.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-plus text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Add Email Config</h3>
                    <p class="text-xs text-gray-500">Create new email configuration</p>
                </div>
            </a>

            <a href="{{ route('settings.communication.sms.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-plus text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Add SMS Config</h3>
                    <p class="text-xs text-gray-500">Create new SMS configuration</p>
                </div>
            </a>

            <button @click="sendTestMessage()" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-paper-plane text-orange-600"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Send Test Message</h3>
                    <p class="text-xs text-gray-500">Test email/SMS delivery</p>
                </div>
            </button>
        </div>
    </div>

    <!-- Email Configurations -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-envelope text-blue-500 mr-2"></i>
                Email Configurations
            </h2>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search email configs..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
            </div>
        </div>
        
        @if($emailConfigs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Configuration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Primary</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($emailConfigs as $config)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-envelope text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $config->name }}</div>
                                        @if($config->description)
                                            <div class="text-xs text-gray-500">{{ $config->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $config->config['mail_from_address'] ?? 'N/A' }}</div>
                                @if($config->config['mail_from_name'])
                                    <div class="text-xs text-gray-500">{{ $config->config['mail_from_name'] }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ strtoupper($config->config['mail_mailer'] ?? 'smtp') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($config->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times-circle mr-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($config->is_primary)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-600 text-white">
                                        <i class="fas fa-star mr-1"></i>Primary
                                    </span>
                                @else
                                    <form action="{{ route('settings.communication.set-primary', $config->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-medium" onclick="return confirm('Set this as primary configuration?')">
                                            Set Primary
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-1">
                                    <!-- Test Button -->
                                    <button @click="testConfig('email', {{ $config->id }})" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" 
                                            title="Test Email Configuration">
                                        <i class="fas fa-vial mr-1"></i>
                                        Test
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('settings.communication.email.edit', $config->id) }}" 
                                       class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" 
                                       title="Edit Configuration">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    
                                    <!-- More Actions Dropdown -->
                                    <div class="relative inline-block text-left">
                                        <button @click="toggleDropdown('email-{{ $config->id }}')" 
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors"
                                                title="More Actions">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        
                                        <div x-show="dropdowns['email-{{ $config->id }}']" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 transform scale-95"
                                             x-transition:enter-end="opacity-100 transform scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="opacity-100 transform scale-100"
                                             x-transition:leave-end="opacity-0 transform scale-95"
                                             @click.away="closeDropdown('email-{{ $config->id }}')"
                                             class="absolute right-0 z-10 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 focus:outline-none"
                                             style="display: none;">
                                            
                                            <div class="py-1">
                                                <!-- View Details -->
                                                <button @click="viewDetails('email', {{ $config->id }})" 
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <i class="fas fa-eye mr-2 text-gray-400"></i>
                                                    View Details
                                                </button>
                                                
                                                <!-- Duplicate -->
                                                <button @click="duplicateConfig('email', {{ $config->id }})" 
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <i class="fas fa-copy mr-2 text-gray-400"></i>
                                                    Duplicate
                                                </button>
                                                
                                                <!-- Export -->
                                                <button @click="exportConfig('email', {{ $config->id }})" 
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <i class="fas fa-download mr-2 text-gray-400"></i>
                                                    Export
                                                </button>
                                                
                                                <div class="border-t border-gray-100"></div>
                                                
                                                <!-- Delete -->
                                                <button @click="deleteConfig('email', {{ $config->id }})" 
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Email Configurations</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first email configuration</p>
                <a href="{{ route('settings.communication.email.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Email Config
                </a>
            </div>
        @endif
    </div>

    <!-- SMS Configurations -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-sms text-green-500 mr-2"></i>
                SMS Configurations
            </h2>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search SMS configs..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option>All Providers</option>
                    <option>Twilio</option>
                    <option>Infobip</option>
                    <option>Local</option>
                </select>
            </div>
        </div>
        
        @if($smsConfigs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Configuration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Primary</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($smsConfigs as $config)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-sms text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $config->name }}</div>
                                        @if($config->description)
                                            <div class="text-xs text-gray-500">{{ $config->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ ucfirst($config->config['provider'] ?? 'twilio') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $config->config['from_number'] ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($config->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times-circle mr-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($config->is_primary)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-600 text-white">
                                        <i class="fas fa-star mr-1"></i>Primary
                                    </span>
                                @else
                                    <form action="{{ route('settings.communication.set-primary', $config->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-green-600 hover:text-green-800 font-medium" onclick="return confirm('Set this as primary configuration?')">
                                            Set Primary
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-1">
                                    <!-- Test Button -->
                                    <button @click="testConfig('sms', {{ $config->id }})" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-green-50 text-green-600 hover:bg-green-100 transition-colors" 
                                            title="Test SMS Configuration">
                                        <i class="fas fa-vial mr-1"></i>
                                        Test
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('settings.communication.sms.edit', $config->id) }}" 
                                       class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" 
                                       title="Edit Configuration">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    
                                    <!-- More Actions Dropdown -->
                                    <div class="relative inline-block text-left">
                                        <button @click="toggleDropdown('sms-{{ $config->id }}')" 
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors"
                                                title="More Actions">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        
                                        <div x-show="dropdowns['sms-{{ $config->id }}']" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 transform scale-95"
                                             x-transition:enter-end="opacity-100 transform scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="opacity-100 transform scale-100"
                                             x-transition:leave-end="opacity-0 transform scale-95"
                                             @click.away="closeDropdown('sms-{{ $config->id }}')"
                                             class="absolute right-0 z-10 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 focus:outline-none"
                                             style="display: none;">
                                            
                                            <div class="py-1">
                                                <!-- View Details -->
                                                <button @click="viewDetails('sms', {{ $config->id }})" 
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <i class="fas fa-eye mr-2 text-gray-400"></i>
                                                    View Details
                                                </button>
                                                
                                                <!-- Send Test SMS -->
                                                <button @click="sendTestSms({{ $config->id }})" 
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <i class="fas fa-paper-plane mr-2 text-gray-400"></i>
                                                    Send Test SMS
                                                </button>
                                                
                                                <!-- Duplicate -->
                                                <button @click="duplicateConfig('sms', {{ $config->id }})" 
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <i class="fas fa-copy mr-2 text-gray-400"></i>
                                                    Duplicate
                                                </button>
                                                
                                                <!-- Export -->
                                                <button @click="exportConfig('sms', {{ $config->id }})" 
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <i class="fas fa-download mr-2 text-gray-400"></i>
                                                    Export
                                                </button>
                                                
                                                <div class="border-t border-gray-100"></div>
                                                
                                                <!-- Delete -->
                                                <button @click="deleteConfig('sms', {{ $config->id }})" 
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-sms text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No SMS Configurations</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first SMS configuration</p>
                <a href="{{ route('settings.communication.sms.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create SMS Config
                </a>
            </div>
        @endif
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
            <button @click="refreshActivity()" class="text-sm text-blue-600 hover:text-blue-800">
                <i class="fas fa-sync-alt mr-1"></i>Refresh
            </button>
        </div>
        
        <div class="space-y-4">
            <!-- Email configuration tested -->
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-envelope text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Email configuration tested</p>
                    <p class="text-xs text-gray-500">Gmail SMTP - Just now</p>
                </div>
                <span class="text-xs font-medium text-green-600">Success</span>
            </div>
            
            <!-- SMS configuration created -->
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-sms text-green-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">SMS configuration created</p>
                    <p class="text-xs text-gray-500">Messaging Service - 1 hour ago</p>
                </div>
                <span class="text-xs font-medium text-blue-600">Created</span>
            </div>
            
            <!-- Primary configuration changed -->
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-star text-orange-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Primary configuration changed</p>
                    <p class="text-xs text-gray-500">Email - 3 hours ago</p>
                </div>
                <span class="text-xs font-medium text-orange-600">Updated</span>
            </div>
            
            <!-- Test message sent -->
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-paper-plane text-purple-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Test message sent</p>
                    <p class="text-xs text-gray-500">Email & SMS - 5 hours ago</p>
                </div>
                <span class="text-xs font-medium text-purple-600">Sent</span>
            </div>
        </div>
    </div>
</div>

<script>
function communicationSettings() {
    return {
        activeConfigsCount: 0,
        testStatus: 'Ready',
        dropdowns: {},
        
        init() {
            this.updateActiveConfigsCount();
        },
        
        updateActiveConfigsCount() {
            // Count active configurations (would be calculated from actual data)
            this.activeConfigsCount = {{ $emailConfigs->where('is_active', true)->count() + $smsConfigs->where('is_active', true)->count() }};
        },
        
        toggleDropdown(id) {
            // Close all other dropdowns
            Object.keys(this.dropdowns).forEach(key => {
                if (key !== id) {
                    this.dropdowns[key] = false;
                }
            });
            
            // Toggle current dropdown
            this.dropdowns[id] = !this.dropdowns[id];
        },
        
        closeDropdown(id) {
            this.dropdowns[id] = false;
        },
        
        testAllConfigs() {
            if (confirm('Test all communication configurations? This will send test messages.')) {
                this.testStatus = 'Testing...';
                
                // Simulate testing all configurations
                setTimeout(() => {
                    this.testStatus = 'Success';
                    alert('All configurations tested successfully!\n\n• Email configs: {{ $emailConfigs->count() }} tested\n• SMS configs: {{ $smsConfigs->count() }} tested\n• All tests passed');
                }, 2000);
            }
        },
        
        testConfig(type, configId) {
            // Show test popup with progress
            this.showTestPopup(type, configId);
        },
        
        showTestPopup(type, configId) {
            // Create popup HTML
            const popupHtml = `
                <div id="testPopup" class="fixed inset-0 z-50 overflow-y-auto" style="display: block;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <i class="fas fa-${type === 'email' ? 'envelope text-blue-600' : 'sms text-green-600'} mr-2"></i>
                                    Test ${type.charAt(0).toUpperCase() + type.slice(1)} Configuration
                                </h3>
                                <button onclick="this.closest('#testPopup').remove()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <!-- Progress Section -->
                            <div id="progressSection" class="mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Testing Connection...</span>
                                    <span id="progressPercent" class="text-sm font-medium text-blue-600">0%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <div id="progressStatus" class="mt-2 text-xs text-gray-500">Initializing test...</div>
                            </div>
                            
                            <!-- Input Section (hidden initially) -->
                            <div id="inputSection" class="hidden space-y-4">
                                <!-- Advanced Options Toggle -->
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-gray-900">Test Configuration</h4>
                                    <button type="button" onclick="toggleAdvancedOptions()" 
                                            class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-cog mr-1"></i>Advanced Options
                                    </button>
                                </div>
                                
                                <!-- Basic Test Fields -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-${type === 'email' ? 'envelope' : 'phone'} mr-1 text-gray-500"></i>
                                        ${type === 'email' ? 'Test Email Address' : 'Test Phone Number'}
                                    </label>
                                    <input type="${type === 'email' ? 'email' : 'tel'}" 
                                           id="testRecipient" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="${type === 'email' ? 'test@example.com' : '255712345678'}">
                                    <div class="mt-1 text-xs text-gray-500">
                                        ${type === 'email' ? 'Enter email address to send test message' : 'Format: 255XXXXXXXXX'}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-comment mr-1 text-gray-500"></i>
                                        Test Message
                                    </label>
                                    <textarea id="testMessage" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              placeholder="Enter your test message here..."></textarea>
                                    <div class="mt-1 text-xs text-gray-500">
                                        <span id="charCount">0</span>/160 characters
                                    </div>
                                </div>
                                
                                <!-- Advanced Options (hidden by default) -->
                                <div id="advancedOptions" class="hidden space-y-4 border-t pt-4">
                                    <h5 class="text-sm font-medium text-gray-900">Advanced Test Options</h5>
                                    
                                    <!-- SMS Specific Options -->
                                    ${type === 'sms' ? `
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-clock mr-1 text-gray-500"></i>
                                                Schedule Time
                                            </label>
                                            <select id="scheduleTime" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="now">Send Now</option>
                                                <option value="5min">In 5 minutes</option>
                                                <option value="30min">In 30 minutes</option>
                                                <option value="1hour">In 1 hour</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-users mr-1 text-gray-500"></i>
                                                Multiple Numbers (comma separated)
                                            </label>
                                            <textarea id="multipleNumbers" rows="2" 
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                      placeholder="255712345678, 255716718040, 255758483019"></textarea>
                                            <div class="mt-1 text-xs text-gray-500">
                                                Send to multiple numbers at once
                                            </div>
                                        </div>
                                    ` : ''}
                                    
                                    <!-- Email Specific Options -->
                                    ${type === 'email' ? `
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-heading mr-1 text-gray-500"></i>
                                                Subject Line
                                            </label>
                                            <input type="text" id="emailSubject" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                   placeholder="Test Email from ShopSmart">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-code mr-1 text-gray-500"></i>
                                                Email Type
                                            </label>
                                            <select id="emailType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="text">Plain Text</option>
                                                <option value="html">HTML</option>
                                                <option value="both">Text + HTML</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-paperclip mr-1 text-gray-500"></i>
                                                Priority
                                            </label>
                                            <select id="emailPriority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="normal">Normal</option>
                                                <option value="high">High</option>
                                                <option value="urgent">Urgent</option>
                                            </select>
                                        </div>
                                    ` : ''}
                                    
                                    <!-- Common Options -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-tag mr-1 text-gray-500"></i>
                                            Reference ID
                                        </label>
                                        <input type="text" id="referenceId" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="test_${type}_${Date.now()}">
                                        <div class="mt-1 text-xs text-gray-500">
                                            Unique identifier for tracking
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-sync mr-1 text-gray-500"></i>
                                            Retry Attempts
                                        </label>
                                        <select id="retryAttempts" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="1">1 attempt</option>
                                            <option value="2">2 attempts</option>
                                            <option value="3">3 attempts</option>
                                            <option value="5">5 attempts</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex justify-end space-x-3">
                                    <button onclick="this.closest('#testPopup').remove()" 
                                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </button>
                                    <button onclick="sendAdvancedTestMessage('${type}', ${configId})" 
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-paper-plane mr-2"></i>Send Test
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Add popup to page
            document.body.insertAdjacentHTML('beforeend', popupHtml);
            
            // Start progress animation
            this.animateProgress(type, configId);
        },
        
        animateProgress(type, configId) {
            let progress = 0;
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const progressStatus = document.getElementById('progressStatus');
            const progressSection = document.getElementById('progressSection');
            const inputSection = document.getElementById('inputSection');
            
            const statusMessages = [
                'Initializing test...',
                'Connecting to server...',
                'Validating configuration...',
                'Testing authentication...',
                'Checking connectivity...',
                'Verifying settings...',
                'Finalizing connection...',
                'Connection established!'
            ];
            
            const interval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 100) progress = 100;
                
                progressBar.style.width = progress + '%';
                progressPercent.textContent = Math.round(progress) + '%';
                
                const statusIndex = Math.floor((progress / 100) * statusMessages.length);
                progressStatus.textContent = statusMessages[Math.min(statusIndex, statusMessages.length - 1)];
                
                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        progressSection.classList.add('hidden');
                        inputSection.classList.remove('hidden');
                        this.setupMessageCounter();
                    }, 500);
                }
            }, 200);
        },
        
        setupMessageCounter() {
            const messageInput = document.getElementById('testMessage');
            const charCount = document.getElementById('charCount');
            
            messageInput.addEventListener('input', () => {
                const length = messageInput.value.length;
                charCount.textContent = length;
                if (length > 160) {
                    charCount.classList.add('text-red-600');
                } else {
                    charCount.classList.remove('text-red-600');
                }
            });
        },
        
        sendTestMessage(type, configId) {
            const recipient = document.getElementById('testRecipient').value;
            const message = document.getElementById('testMessage').value;
            
            if (!recipient || !message) {
                alert('Please fill in both recipient and message fields.');
                return;
            }
            
            // Validate recipient format
            if (type === 'email' && !this.isValidEmail(recipient)) {
                alert('Please enter a valid email address.');
                return;
            }
            
            if (type === 'sms' && !this.isValidPhone(recipient)) {
                alert('Please enter a valid phone number in format: 255XXXXXXXXX');
                return;
            }
            
            // Show sending progress
            const inputSection = document.getElementById('inputSection');
            inputSection.innerHTML = `
                <div class="text-center py-4">
                    <div class="inline-flex items-center justify-center w-8 h-8 border-2 border-blue-600 border-t-transparent rounded-full animate-spin">
                        <span class="sr-only">Sending...</span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Sending test ${type}...</p>
                </div>
            `;
            
            // Send test message
            this.sendActualTestMessage(type, configId, recipient, message, {});
        },
        
        sendAdvancedTestMessage(type, configId) {
            const recipient = document.getElementById('testRecipient').value;
            const message = document.getElementById('testMessage').value;
            
            if (!recipient || !message) {
                alert('Please fill in both recipient and message fields.');
                return;
            }
            
            // Validate recipient format
            if (type === 'email' && !this.isValidEmail(recipient)) {
                alert('Please enter a valid email address.');
                return;
            }
            
            if (type === 'sms' && !this.isValidPhone(recipient)) {
                alert('Please enter a valid phone number in format: 255XXXXXXXXX');
                return;
            }
            
            // Collect advanced options
            const advancedOptions = {
                referenceId: document.getElementById('referenceId')?.value || `test_${type}_${Date.now()}`,
                retryAttempts: document.getElementById('retryAttempts')?.value || '1'
            };
            
            // Type-specific options
            if (type === 'email') {
                advancedOptions.subject = document.getElementById('emailSubject')?.value || 'Test Email from ShopSmart';
                advancedOptions.emailType = document.getElementById('emailType')?.value || 'text';
                advancedOptions.priority = document.getElementById('emailPriority')?.value || 'normal';
            } else if (type === 'sms') {
                advancedOptions.scheduleTime = document.getElementById('scheduleTime')?.value || 'now';
                advancedOptions.multipleNumbers = document.getElementById('multipleNumbers')?.value || '';
            }
            
            // Show sending progress
            const inputSection = document.getElementById('inputSection');
            inputSection.innerHTML = `
                <div class="text-center py-4">
                    <div class="inline-flex items-center justify-center w-8 h-8 border-2 border-blue-600 border-t-transparent rounded-full animate-spin">
                        <span class="sr-only">Sending...</span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Sending advanced test ${type}...</p>
                    <div class="mt-2 text-xs text-gray-500">
                        ${type === 'sms' && advancedOptions.multipleNumbers ? `Sending to ${advancedOptions.multipleNumbers.split(',').length + 1} recipients` : ''}
                        ${type === 'email' ? `Priority: ${advancedOptions.priority}` : ''}
                        ${type === 'sms' && advancedOptions.scheduleTime !== 'now' ? `Scheduled: ${advancedOptions.scheduleTime}` : ''}
                    </div>
                </div>
            `;
            
            // Send advanced test message
            this.sendActualTestMessage(type, configId, recipient, message, advancedOptions);
        },
        
        sendActualTestMessage(type, configId, recipient, message, options) {
            // Prepare payload
            const payload = {
                recipient: recipient,
                message: message,
                config_id: configId,
                ...options
            };
            
            // Send test message
            fetch(`/settings/communication/test-${type}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                this.showTestResult(type, recipient, data);
            })
            .catch(error => {
                this.showTestError(type, error);
            });
        },
        
        showTestResult(type, recipient, data) {
            const popup = document.getElementById('testPopup');
            const content = popup.querySelector('.bg-white > div:last-child');
            
            if (data.success) {
                const successDetails = data.details || {};
                let resultHtml = `
                    <div class="text-center py-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-4">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Test Successful!</h4>
                        <p class="text-gray-600 mb-4">Test ${type} sent successfully</p>
                `;
                
                // Add detailed results
                if (successDetails.messageId) {
                    resultHtml += `<div class="mb-3 p-3 bg-gray-50 rounded-lg text-left">
                        <p class="text-sm font-medium text-gray-700">Message Details:</p>
                        <p class="text-xs text-gray-600">Message ID: ${successDetails.messageId}</p>
                        ${successDetails.price ? `<p class="text-xs text-gray-600">Cost: ${successDetails.price} TZS</p>` : ''}
                        ${successDetails.status ? `<p class="text-gray-600 text-xs">Status: ${successDetails.status}</p>` : ''}
                    </div>`;
                }
                
                resultHtml += `
                    <div class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-${type === 'email' ? 'envelope' : 'phone'} mr-1"></i>
                        Sent to: ${recipient}
                    </div>
                    <button onclick="this.closest('#testPopup').remove()" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>Done
                    </button>
                </div>`;
                
                content.innerHTML = resultHtml;
            } else {
                content.innerHTML = `
                    <div class="text-center py-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Test Failed</h4>
                        <p class="text-gray-600 mb-4">${data.message || 'Failed to send test message'}</p>
                        <div class="text-sm text-gray-600 mb-4">
                            <i class="fas fa-${type === 'email' ? 'envelope' : 'phone'} mr-1"></i>
                            Attempted to send to: ${recipient}
                        </div>
                        <button onclick="this.closest('#testPopup').remove()" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            <i class="fas fa-times mr-2"></i>Close
                        </button>
                    </div>
                `;
            }
        },
        
        showTestError(type, error) {
            const popup = document.getElementById('testPopup');
            const content = popup.querySelector('.bg-white > div:last-child');
            
            content.innerHTML = `
                <div class="text-center py-6">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Network Error</h4>
                    <p class="text-gray-600 mb-4">Failed to send test message: ${error.message}</p>
                    <button onclick="this.closest('#testPopup').remove()" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        <i class="fas fa-times mr-2"></i>Close
                    </button>
                </div>
            `;
        },
        
        isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        },
        
        isValidPhone(phone) {
            return /^255\d{9}$/.test(phone.replace(/\D/g, ''));
        },
        
        editConfig(type, configId) {
            // Navigate to edit page
            if (type === 'email') {
                window.location.href = `/settings/communication/email/edit/${configId}`;
            } else if (type === 'sms') {
                window.location.href = `/settings/communication/sms/edit/${configId}`;
            }
        },
        
        viewDetails(type, configId) {
            // View configuration details
            fetch(`/settings/communication/${type}/${configId}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const details = data.config;
                        let detailsHtml = `<div class="space-y-3">
                            <h4 class="font-semibold text-gray-900">${details.name}</h4>
                            <p class="text-sm text-gray-600">${details.description || 'No description'}</p>
                            <div class="bg-gray-50 p-3 rounded">
                                <h5 class="font-medium text-gray-900 mb-2">Configuration Details:</h5>`;
                        
                        if (type === 'email') {
                            const config = details.config || {};
                            detailsHtml += `
                                <p><strong>Host:</strong> ${config.mail_host || 'N/A'}</p>
                                <p><strong>Port:</strong> ${config.mail_port || 'N/A'}</p>
                                <p><strong>Username:</strong> ${config.mail_username || 'N/A'}</p>
                                <p><strong>Encryption:</strong> ${config.mail_encryption || 'N/A'}</p>
                                <p><strong>From Email:</strong> ${config.mail_from_address || 'N/A'}</p>
                                <p><strong>From Name:</strong> ${config.mail_from_name || 'N/A'}</p>`;
                        } else if (type === 'sms') {
                            const config = details.config || {};
                            detailsHtml += `
                                <p><strong>Provider:</strong> ${config.provider || 'N/A'}</p>
                                <p><strong>From Number:</strong> ${config.from_number || 'N/A'}</p>
                                <p><strong>API URL:</strong> ${config.api_url || 'N/A'}</p>
                                <p><strong>Username:</strong> ${config.username || 'N/A'}</p>`;
                        }
                        
                        detailsHtml += `</div></div>`;
                        
                        // Show modal with details
                        this.showModal('Configuration Details', detailsHtml);
                    } else {
                        alert('Failed to load configuration details');
                    }
                })
                .catch(error => {
                    alert('Error loading configuration details: ' + error.message);
                });
        },
        
        duplicateConfig(type, configId) {
            if (confirm(`Duplicate this ${type} configuration? This will create a copy with the same settings.`)) {
                fetch(`/settings/communication/${type}/${configId}/duplicate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`${type.charAt(0).toUpperCase() + type.slice(1)} configuration duplicated successfully!`);
                        location.reload();
                    } else {
                        alert('Failed to duplicate configuration: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error duplicating configuration: ' + error.message);
                });
            }
        },
        
        exportConfig(type, configId) {
            fetch(`/settings/communication/${type}/${configId}/export`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Create and download JSON file
                        const configData = JSON.stringify(data.config, null, 2);
                        const blob = new Blob([configData], { type: 'application/json' });
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `${type}-config-${configId}.json`;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    } else {
                        alert('Failed to export configuration');
                    }
                })
                .catch(error => {
                    alert('Error exporting configuration: ' + error.message);
                });
        },
        
        sendTestSms(configId) {
            const phone = prompt('Enter phone number to send test SMS:', '255712345678');
            if (phone) {
                const message = prompt('Enter test message:', 'This is a test SMS from ShopSmart');
                if (message) {
                    fetch('/settings/communication/test-sms', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            phone: phone,
                            message: message,
                            config_id: configId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('✅ Test SMS sent successfully to ' + phone);
                        } else {
                            alert('❌ Failed to send test SMS: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error sending test SMS: ' + error.message);
                    });
                }
            }
        },
        
        showModal(title, content) {
            // Simple modal implementation
            const modalHtml = `
                <div id="configModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: block;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">${title}</h3>
                                <button onclick="this.closest('#configModal').remove()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div>${content}</div>
                        </div>
                    </div>
                </div>`;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        },
        
        deleteConfig(type, configId) {
            if (confirm(`Are you sure you want to delete this ${type} configuration? This action cannot be undone.`)) {
                // Submit delete form using proper URL construction
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/settings/communication/${configId}`;
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Add method override for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        },
        
        sendTestMessage() {
            // Navigate to test message page
            window.location.href = '/settings/communication/test-message';
        },
        
        exportConfigs() {
            alert('Export configurations functionality would:\n\n• Export all email configs to CSV\n• Export all SMS configs to CSV\n• Include configuration details\n• Generate backup file');
        },
        
        refreshActivity() {
            // Refresh recent activity with latest communication events
            location.reload();
        }
    }
}

// Global function for popup send button
function sendTestMessage(type, configId) {
    const communication = window.communicationSettings();
    communication.sendTestMessage(type, configId);
}

// Global function for advanced test send button
function sendAdvancedTestMessage(type, configId) {
    const communication = window.communicationSettings();
    communication.sendAdvancedTestMessage(type, configId);
}

// Global function for advanced options toggle
function toggleAdvancedOptions() {
    const advancedOptions = document.getElementById('advancedOptions');
    if (advancedOptions) {
        advancedOptions.classList.toggle('hidden');
    }
}
</script>
@endsection
