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
                window.location.href = '{{ route('settings.backup.clear-cache') }}';
            }
        },
        optimizeDb() {
            if (confirm('Are you sure you want to optimize the database? This may take a few minutes.')) {
                window.location.href = '{{ route('settings.backup.optimize-db') }}';
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Users</h3>
                        <p class="text-sm text-gray-600">Manage system users</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('settings.roles') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-blue-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Roles & Permissions</h3>
                        <p class="text-sm text-gray-600">Configure user roles and access</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- System Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">System</h2>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('settings.system') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-green-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">System Preferences</h3>
                        <p class="text-sm text-gray-600">Modules, notifications, theme</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Financial Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Financial</h2>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('settings.financial') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-yellow-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-yellow-600 transition-colors">Financial Settings</h3>
                        <p class="text-sm text-gray-600">Tax rates, payment methods</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Inventory Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Inventory</h2>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('settings.inventory') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-indigo-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Inventory Settings</h3>
                        <p class="text-sm text-gray-600">Stock alerts, units, categories</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Quotations Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Quotations</h2>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('settings.quotations') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-pink-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center group-hover:bg-pink-200 transition-colors">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-pink-600 transition-colors">Quotation Settings</h3>
                        <p class="text-sm text-gray-600">Terms, expiry, templates</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-pink-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Notifications</h2>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('settings.notifications') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-red-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-red-600 transition-colors">Notifications</h3>
                        <p class="text-sm text-gray-600">Alerts and reminders</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Communication Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Communication</h2>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('settings.communication.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-teal-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-200 transition-colors">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-teal-600 transition-colors">Email & SMS</h3>
                        <p class="text-sm text-gray-600">Configure email and SMS settings</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-teal-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Backup & Maintenance Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Backup & Maintenance</h2>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('settings.backup') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-gray-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-gray-600 transition-colors">Backup & Maintenance</h3>
                        <p class="text-sm text-gray-600">Database backup, logs</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
