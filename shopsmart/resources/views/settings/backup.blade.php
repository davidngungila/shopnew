@extends('layouts.app')

@section('title', 'Backup & System Maintenance')

@section('content')
<div class="space-y-6" x-data="backupSystem()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Backup & System Maintenance</h1>
            <p class="text-gray-600 mt-1">Manage backups, system health, and maintenance tasks</p>
        </div>
        <div class="flex gap-2">
            <button @click="scheduleBackup()" class="px-4 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-clock mr-2"></i>Schedule Backup
            </button>
            <a href="{{ route('settings.index') }}" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors" style="background-color: #6b7280;">
                <i class="fas fa-arrow-left mr-2"></i>Back
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

    <!-- System Health Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">System Health</p>
                    <p class="text-2xl font-bold text-green-600">Excellent</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-heartbeat text-green-500"></i> 
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
                    <p class="text-sm font-medium text-gray-500">Database Size</p>
                    <p class="text-2xl font-bold text-blue-600">2.4 GB</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-database text-blue-500"></i> 
                        Growing slowly
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-database text-blue-600"></i>
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
                        Automated success
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-purple-600"></i>
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
                    <i class="fas fa-hdd text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto px-6">
                <button @click="activeTab = 'backup'" 
                        :class="activeTab === 'backup' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-database mr-2"></i>Database Backup
                </button>
                <button @click="activeTab = 'automation'" 
                        :class="activeTab === 'automation' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-robot mr-2"></i>Automation
                </button>
                <button @click="activeTab = 'maintenance'" 
                        :class="activeTab === 'maintenance' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-tools mr-2"></i>Maintenance
                </button>
                <button @click="activeTab = 'logs'" 
                        :class="activeTab === 'logs' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-file-alt mr-2"></i>System Logs
                </button>
            </nav>
        </div>

    <!-- Tab Content -->
    <div class="mt-6">
        <!-- Database Backup Tab -->
        <div x-show="activeTab === 'backup'" x-transition>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Create Backup Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Create Backup</h3>
                                <p class="text-sm text-gray-600">Manual database backup</p>
                            </div>
                        </div>
                        <form action="{{ route('settings.backup.create') }}" method="POST" id="backupForm">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Backup Type</label>
                                    <select name="backup_type" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                                        <option value="full">Full Backup (Database + Files)</option>
                                        <option value="database" selected>Database Only</option>
                                        <option value="files">Files Only</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Compression</label>
                                    <select name="compression" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                                        <option value="none">None</option>
                                        <option value="gzip" selected>Gzip (Recommended)</option>
                                        <option value="zip">ZIP</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Create Backup Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Backup History -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Backup History</h3>
                            <button class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                                Refresh
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <div>2024-01-15 14:30:25</div>
                                            <div class="text-xs text-gray-500">2 hours ago</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Database
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">15.2 MB</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Success
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button class="text-blue-600 hover:text-blue-700" title="Download">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </button>
                                                <button class="text-green-600 hover:text-green-700" title="Restore">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                </button>
                                                <button class="text-red-600 hover:text-red-700" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <div>2024-01-14 02:00:00</div>
                                            <div class="text-xs text-gray-500">1 day ago</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Full
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">245.8 MB</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Success
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button class="text-blue-600 hover:text-blue-700" title="Download">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </button>
                                                <button class="text-green-600 hover:text-green-700" title="Restore">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                </button>
                                                <button class="text-red-600 hover:text-red-700" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-sm text-gray-600">
                            <p>💡 <strong>Tip:</strong> Keep regular backups to protect your data. Restore backups only when necessary.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Automation Tab -->
        <div x-show="activeTab === 'automation'" x-transition>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Automated Backup Settings</h3>
                <form action="{{ route('settings.backup.automation') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Enable Automated Backups</h4>
                                <p class="text-sm text-gray-600">Automatically create backups on a schedule</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="auto_backup" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Backup Frequency</label>
                                <select name="backup_frequency" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                                    <option value="daily">Daily</option>
                                    <option value="weekly" selected>Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Backup Time</label>
                                <input type="time" name="backup_time" value="02:00" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Retention Period</label>
                                <select name="retention_days" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                                    <option value="7">7 days</option>
                                    <option value="14">14 days</option>
                                    <option value="30" selected>30 days</option>
                                    <option value="60">60 days</option>
                                    <option value="90">90 days</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Backup Type</label>
                                <select name="auto_backup_type" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                                    <option value="database" selected>Database Only</option>
                                    <option value="full">Full Backup</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-yellow-900">Email Notifications</h4>
                                <p class="text-sm text-yellow-700">Receive email notifications after backup completion</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="backup_email_notifications" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                                Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Maintenance Tab -->
        <div x-show="activeTab === 'maintenance'" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Clear Cache -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Clear Cache</h3>
                            <p class="text-sm text-gray-600">Application cache</p>
                        </div>
                    </div>
                    <form action="{{ route('settings.backup.clear-cache') }}" method="POST" onsubmit="return confirm('Clear all application cache?')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                            Clear Cache
                        </button>
                    </form>
                </div>

                <!-- Clear Views -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Clear Views</h3>
                            <p class="text-sm text-gray-600">Compiled views cache</p>
                        </div>
                    </div>
                    <form action="{{ route('settings.backup.clear-views') }}" method="POST" onsubmit="return confirm('Clear compiled views cache?')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Clear Views
                        </button>
                    </form>
                </div>

                <!-- Clear Routes -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Clear Routes</h3>
                            <p class="text-sm text-gray-600">Route cache</p>
                        </div>
                    </div>
                    <form action="{{ route('settings.backup.clear-routes') }}" method="POST" onsubmit="return confirm('Clear route cache?')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Clear Routes
                        </button>
                    </form>
                </div>

                <!-- Clear Config -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Clear Config</h3>
                            <p class="text-sm text-gray-600">Configuration cache</p>
                        </div>
                    </div>
                    <form action="{{ route('settings.backup.clear-config') }}" method="POST" onsubmit="return confirm('Clear configuration cache?')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Clear Config
                        </button>
                    </form>
                </div>

                <!-- Optimize Database -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Optimize Database</h3>
                            <p class="text-sm text-gray-600">Optimize database tables</p>
                        </div>
                    </div>
                    <form action="{{ route('settings.backup.optimize-db') }}" method="POST" onsubmit="return confirm('Optimize database tables? This may take a few minutes.')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Optimize Database
                        </button>
                    </form>
                </div>

                <!-- Clear All -->
                <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Clear All Caches</h3>
                            <p class="text-sm text-gray-600">Clear all caches at once</p>
                        </div>
                    </div>
                    <form action="{{ route('settings.backup.clear-all') }}" method="POST" onsubmit="return confirm('Clear ALL caches? This will clear application, views, routes, and config caches.')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Clear All Caches
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- System Logs Tab -->
        <div x-show="activeTab === 'logs'" x-transition>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">System Logs</h3>
                    <div class="flex items-center space-x-2">
                        <select class="text-sm rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <option>All Logs</option>
                            <option>Error</option>
                            <option>Warning</option>
                            <option>Info</option>
                        </select>
                        <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                            Refresh
                        </button>
                    </div>
                </div>
                <div class="bg-gray-900 rounded-lg p-4 font-mono text-sm text-green-400 overflow-x-auto max-h-96 overflow-y-auto">
                    <div class="space-y-1">
                        <div>[2024-01-15 14:30:25] INFO: Application started successfully</div>
                        <div>[2024-01-15 14:30:24] INFO: Database connection established</div>
                        <div>[2024-01-15 14:30:23] INFO: Cache cleared successfully</div>
                        <div>[2024-01-15 14:25:10] WARNING: Low stock alert for Product #123</div>
                        <div>[2024-01-15 14:20:05] INFO: Backup created successfully (15.2 MB)</div>
                        <div>[2024-01-15 14:15:00] INFO: User login: admin@example.com</div>
                        <div>[2024-01-15 14:10:30] ERROR: Failed to send email notification</div>
                        <div>[2024-01-15 14:05:15] INFO: Sale completed: #INV-2024-001</div>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                    <p>Showing last 100 log entries</p>
                    <button class="text-purple-600 hover:text-purple-700 font-medium">
                        Download Logs
                    </button>
                </div>
            </div>
        </div>

        <!-- System Info Tab -->
        <div x-show="activeTab === 'system'" x-transition>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- System Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">System Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">PHP Version</span>
                            <span class="font-medium text-gray-900">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Laravel Version</span>
                            <span class="font-medium text-gray-900">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Database</span>
                            <span class="font-medium text-gray-900">
                                @php
                                    try {
                                        $dbVersion = DB::select('SELECT VERSION() as version')[0]->version ?? 'Unknown';
                                        echo 'MySQL ' . $dbVersion;
                                    } catch (\Exception $e) {
                                        echo 'MySQL';
                                    }
                                @endphp
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Server</span>
                            <span class="font-medium text-gray-900">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Environment</span>
                            <span class="font-medium text-gray-900">{{ app()->environment() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Timezone</span>
                            <span class="font-medium text-gray-900">{{ config('app.timezone') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Locale</span>
                            <span class="font-medium text-gray-900">{{ config('app.locale') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">Debug Mode</span>
                            <span class="font-medium {{ config('app.debug') ? 'text-red-600' : 'text-green-600' }}">
                                {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- System Health -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">System Health</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Disk Usage</span>
                                <span class="text-sm font-medium text-gray-900">75%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Database Size</span>
                                <span class="text-sm font-medium text-gray-900">125.5 MB</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Cache Size</span>
                                <span class="text-sm font-medium text-gray-900">12.3 MB</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900">System Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Healthy
                                </span>
                            </div>
                            <div class="text-xs text-gray-600 space-y-1">
                                <div>✓ Database connection: OK</div>
                                <div>✓ Cache system: OK</div>
                                <div>✓ Storage: OK</div>
                                <div>✓ Queue system: OK</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle backup form submission
    document.getElementById('backupForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const button = form.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creating Backup...';
        
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup created successfully!');
                location.reload();
            } else {
                alert('Error creating backup: ' + (data.message || 'Unknown error'));
                button.disabled = false;
                button.innerHTML = originalText;
            }
        })
        .catch(error => {
            alert('Error creating backup: ' + error.message);
            button.disabled = false;
            button.innerHTML = originalText;
        });
    });
</script>
@endpush
@endsection
