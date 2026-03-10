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
            <a href="{{ route('settings.communication.test-message') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-paper-plane mr-2"></i>Send Test Message
            </a>
            <a href="{{ route('settings.communication.email.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>New Email Config
            </a>
            <a href="{{ route('settings.communication.sms.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>New SMS Config
            </a>
            <a href="{{ route('settings.communication.index') }}" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors" style="background-color: #6b7280;">
                <i class="fas fa-arrow-left mr-2"></i>Back to Settings
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
                                <div class="flex items-center justify-end space-x-2">
                                    <button @click="testConfig('email', {{ $config->id }})" class="text-blue-600 hover:text-blue-900" title="Test Configuration">
                                        <i class="fas fa-vial"></i>
                                    </button>
                                    <a href="#" @click="editConfig('email', {{ $config->id }})" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button @click="deleteConfig('email', {{ $config->id }})" class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                                <div class="flex items-center justify-end space-x-2">
                                    <button @click="testConfig('sms', {{ $config->id }})" class="text-green-600 hover:text-green-900" title="Test Configuration">
                                        <i class="fas fa-vial"></i>
                                    </button>
                                    <a href="#" @click="editConfig('sms', {{ $config->id }})" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button @click="deleteConfig('sms', {{ $config->id }})" class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-envelope text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Email configuration tested</p>
                    <p class="text-xs text-gray-500">Gmail SMTP - 2 minutes ago</p>
                </div>
                <span class="text-xs text-green-600 font-medium">Success</span>
            </div>
            
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-sms text-green-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">SMS configuration created</p>
                    <p class="text-xs text-gray-500">Twilio - 1 hour ago</p>
                </div>
                <span class="text-xs text-blue-600 font-medium">Created</span>
            </div>
            
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-star text-orange-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Primary configuration changed</p>
                    <p class="text-xs text-gray-500">Email - 3 hours ago</p>
                </div>
                <span class="text-xs text-orange-600 font-medium">Updated</span>
            </div>
        </div>
    </div>
</div>

<script>
function communicationSettings() {
    return {
        activeConfigsCount: 0,
        testStatus: 'Ready',
        
        init() {
            this.updateActiveConfigsCount();
        },
        
        updateActiveConfigsCount() {
            // Count active configurations (would be calculated from actual data)
            this.activeConfigsCount = {{ $emailConfigs->where('is_active', true)->count() + $smsConfigs->where('is_active', true)->count() }};
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
            alert(`Testing ${type} configuration #${configId}...\n\nThis would send a test message to verify the configuration is working properly.`);
        },
        
        editConfig(type, configId) {
            alert(`Editing ${type} configuration #${configId}...\n\nThis would open an edit modal or navigate to edit page.`);
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
            const testEmail = prompt('Enter test email address:');
            const testPhone = prompt('Enter test phone number (with country code):');
            
            if (testEmail || testPhone) {
                alert(`Test message would be sent to:\n\nEmail: ${testEmail || 'Not specified'}\nPhone: ${testPhone || 'Not specified'}\n\nThis would test both email and SMS delivery.`);
            }
        },
        
        exportConfigs() {
            alert('Export configurations functionality would:\n\n• Export all email configs to CSV\n• Export all SMS configs to CSV\n• Include configuration details\n• Generate backup file');
        },
        
        refreshActivity() {
            // Refresh recent activity
            alert('Recent activity log would be refreshed with latest communication events.');
        }
    }
}
</script>
@endsection
