@extends('layouts.app')

@section('title', 'System Settings & Configuration')

@section('content')
<div class="space-y-8" x-data="settingsDashboard()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">System Settings</h1>
            <p class="text-gray-600 mt-1">Configure and manage your ShopSmart system configuration</p>
        </div>
        <div class="flex gap-2">
            <button @click="exportSettings()" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2 hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-download"></i>
                <span>Export Settings</span>
            </button>
            <button @click="importSettings()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-upload mr-2"></i>Import
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Users</p>
                    <p class="text-2xl font-bold text-blue-600">{{ App\Models\User::count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-users text-blue-500"></i> 
                        Registered accounts
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">System Health</p>
                    <p class="text-2xl font-bold text-green-600">Good</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-check-circle text-green-500"></i> 
                        All systems operational
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-heartbeat text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Last Backup</p>
                    <p class="text-2xl font-bold text-purple-600">2h</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-clock text-purple-500"></i> 
                        Automated backup
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-database text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Storage Used</p>
                    <p class="text-2xl font-bold text-orange-600">68%</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-hdd text-orange-500"></i> 
                        6.8 GB of 10 GB
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-pie text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- General Settings Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">General Configuration</h2>
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="text-sm text-gray-500">Basic system settings</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- General Settings -->
            <a href="{{ route('settings.general') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-purple-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-cog text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">General Settings</h3>
                        <p class="text-sm text-gray-600">Company info, logo, currency</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-purple-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-check-circle mr-1"></i>Configured</span>
                    <span class="text-purple-600">Last updated: 2 days ago</span>
                </div>
            </a>

            <!-- Financial Settings -->
            <a href="{{ route('settings.financial') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-green-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Financial Settings</h3>
                        <p class="text-sm text-gray-600">Tax rates, payment methods</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-green-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-check-circle mr-1"></i>Configured</span>
                    <span class="text-green-600">Last updated: 1 week ago</span>
                </div>
            </a>

            <!-- System Settings -->
            <a href="{{ route('settings.system') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-blue-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-server text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">System Settings</h3>
                        <p class="text-sm text-gray-600">Modules, themes, performance</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-check-circle mr-1"></i>Configured</span>
                    <span class="text-blue-600">Last updated: 3 days ago</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Users & Roles Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">User Management</h2>
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="text-sm text-gray-500">Access control and permissions</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Users -->
            <a href="{{ route('settings.users') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-blue-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">User Management</h3>
                        <p class="text-sm text-gray-600">Manage user accounts</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-users mr-1"></i>{{ App\Models\User::count() }} users</span>
                    <span class="text-blue-600">Active: {{ App\Models\User::where('status', 'active')->count() }}</span>
                </div>
            </a>

            <!-- Roles -->
            <a href="{{ route('settings.roles') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-orange-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                        <i class="fas fa-user-shield text-orange-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">Roles & Permissions</h3>
                        <p class="text-sm text-gray-600">Access control management</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-orange-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-shield-alt mr-1"></i>5 roles</span>
                    <span class="text-orange-600">Last updated: 1 week ago</span>
                </div>
            </a>

            <!-- Communication -->
            <a href="{{ route('settings.communication.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-pink-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center group-hover:bg-pink-200 transition-colors">
                        <i class="fas fa-envelope text-pink-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-pink-600 transition-colors">Communication</h3>
                        <p class="text-sm text-gray-600">Email & SMS settings</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-pink-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-check-circle mr-1"></i>Configured</span>
                    <span class="text-pink-600">2 providers</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Operations Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Operations</h2>
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="text-sm text-gray-500">Business operations settings</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Inventory -->
            <a href="{{ route('settings.inventory') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-teal-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-200 transition-colors">
                        <i class="fas fa-warehouse text-teal-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-teal-600 transition-colors">Inventory Settings</h3>
                        <p class="text-sm text-gray-600">Stock alerts, units</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-teal-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-box mr-1"></i>{{ App\Models\Product::count() }} products</span>
                    <span class="text-teal-600">Low stock: 12</span>
                </div>
            </a>

            <!-- Quotations -->
            <a href="{{ route('settings.quotations') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-indigo-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                        <i class="fas fa-file-invoice text-indigo-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Quotation Settings</h3>
                        <p class="text-sm text-gray-600">Terms, expiry, prefixes</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-indigo-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-file-alt mr-1"></i>Active</span>
                    <span class="text-indigo-600">Last updated: 2 weeks ago</span>
                </div>
            </a>

            <!-- Notifications -->
            <a href="{{ route('settings.notifications') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-yellow-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                        <i class="fas fa-bell text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-yellow-600 transition-colors">Notifications</h3>
                        <p class="text-sm text-gray-600">Alerts and reminders</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-yellow-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-bell mr-1"></i>4 alerts</span>
                    <span class="text-yellow-600">Enabled</span>
                </div>
            </a>
        </div>
    </div>

    <!-- System Maintenance Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">System Maintenance</h2>
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="text-sm text-gray-500">Backup and performance</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Backup -->
            <a href="{{ route('settings.backup') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-red-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                        <i class="fas fa-database text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-red-600 transition-colors">Backup & Recovery</h3>
                        <p class="text-sm text-gray-600">Database backups</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-red-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Last: 2 hours ago</span>
                    <span class="text-red-600">Automated</span>
                </div>
            </a>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tools text-gray-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                        <p class="text-sm text-gray-600">System maintenance</p>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    <button @click="clearCache()" class="w-full px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                        <i class="fas fa-broom mr-1"></i>Clear Cache
                    </button>
                    <button @click="optimizeDb()" class="w-full px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition-colors">
                        <i class="fas fa-tachometer-alt mr-1"></i>Optimize Database
                    </button>
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-info text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">System Information</h3>
                        <p class="text-sm text-gray-600">Current status</p>
                    </div>
                </div>
                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Version:</span>
                        <span class="font-medium">v2.1.0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">PHP:</span>
                        <span class="font-medium">8.2.15</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">MySQL:</span>
                        <span class="font-medium">8.0.32</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Uptime:</span>
                        <span class="font-medium">15 days</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Settings Activity</h3>
            <button @click="viewAllActivity()" class="text-sm text-blue-600 hover:text-blue-800">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-cog text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">General settings updated</p>
                        <p class="text-xs text-gray-500">Admin • 2 hours ago</p>
                    </div>
                </div>
                <span class="text-xs text-green-600">Success</span>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">New user added</p>
                        <p class="text-xs text-gray-500">Manager • 5 hours ago</p>
                    </div>
                </div>
                <span class="text-xs text-blue-600">Created</span>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-database text-purple-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Automatic backup completed</p>
                        <p class="text-xs text-gray-500">System • 2 hours ago</p>
                    </div>
                </div>
                <span class="text-xs text-purple-600">Automated</span>
            </div>
        </div>
    </div>
</div>

<script>
function settingsDashboard() {
    return {
        exportSettings() {
            // Export settings functionality - use backup route for now
            window.location.href = '{{ route('settings.backup') }}';
        },
        importSettings() {
            // Import settings functionality
            alert('Import Settings dialog would open here');
        },
        clearCache() {
            if (confirm('Are you sure you want to clear all caches?')) {
                // Create a form and submit via POST
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('settings.backup.clear-cache') }}';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        },
        optimizeDb() {
            if (confirm('Are you sure you want to optimize the database? This may take a few minutes.')) {
                // Create a form and submit via POST
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('settings.backup.optimize-db') }}';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        },
        viewAllActivity() {
            // View all activity
            alert('Activity log would open here');
        }
    }
}
</script>
@endsection
