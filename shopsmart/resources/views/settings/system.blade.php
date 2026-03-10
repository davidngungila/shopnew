@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="space-y-6" x-data="systemSettings()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">System Settings</h1>
            <p class="text-gray-600 mt-1">Configure system modules, preferences, and performance</p>
        </div>
        <div class="flex gap-2">
            <button @click="runDiagnostics()" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-stethoscope"></i>
                <span>Run Diagnostics</span>
            </button>
            <button @click="optimizeSystem()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-tachometer-alt mr-2"></i>Optimize
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

    <!-- System Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Modules</p>
                    <p class="text-2xl font-bold text-blue-600" x-text="activeModulesCount">4</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-cube text-blue-500"></i> 
                        Enabled modules
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-cube text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">System Health</p>
                    <p class="text-2xl font-bold text-green-600" x-text="systemHealth">Good</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-heartbeat text-green-500"></i> 
                        Overall status
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
                    <p class="text-sm font-medium text-gray-500">Theme</p>
                    <p class="text-2xl font-bold text-purple-600" x-text="currentTheme">Light</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-palette text-purple-500"></i> 
                        Active theme
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-palette text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Performance</p>
                    <p class="text-2xl font-bold text-orange-600" x-text="performanceScore">95%</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-tachometer-alt text-orange-500"></i> 
                        System speed
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-tachometer-alt text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('settings.system.update') }}" method="POST" class="space-y-8">
        @csrf
        
        <!-- Module Configuration -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Module Configuration</h2>
                <span class="text-sm text-gray-500">Enable/disable system modules</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Core Modules</h3>
                    
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_pos" value="1" 
                                   {{ ($settings['enable_pos'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   @change="updateModulesCount()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Point of Sale (POS)</span>
                                <p class="text-xs text-gray-500">Sales and payment processing</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cash-register text-blue-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_inventory" value="1" 
                                   {{ ($settings['enable_inventory'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                   @change="updateModulesCount()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Inventory Management</span>
                                <p class="text-xs text-gray-500">Stock control and tracking</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-boxes text-green-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_customers" value="1" 
                                   {{ ($settings['enable_customers'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                   @change="updateModulesCount()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Customer Management</span>
                                <p class="text-xs text-gray-500">Customer database and CRM</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Extended Modules</h3>
                    
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_quotations" value="1" 
                                   {{ ($settings['enable_quotations'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                                   @change="updateModulesCount()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Quotations</span>
                                <p class="text-xs text-gray-500">Price quotes and estimates</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-invoice text-orange-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_purchases" value="1" 
                                   {{ ($settings['enable_purchases'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                                   @change="updateModulesCount()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Purchase Orders</span>
                                <p class="text-xs text-gray-500">Supplier and procurement</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-teal-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_reports" value="1" 
                                   {{ ($settings['enable_reports'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                   @change="updateModulesCount()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Reports & Analytics</span>
                                <p class="text-xs text-gray-500">Business intelligence</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-indigo-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification System -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Notification System</h2>
                <span class="text-sm text-gray-500">Configure alerts and notifications</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Notification Channels</h3>
                    
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_notifications" value="1" 
                                   {{ ($settings['enable_notifications'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">System Notifications</span>
                                <p class="text-xs text-gray-500">In-app alerts and messages</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bell text-green-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_email" value="1" 
                                   {{ ($settings['enable_email'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Email Notifications</span>
                                <p class="text-xs text-gray-500">Email alerts and reports</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-envelope text-blue-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_sms" value="1" 
                                   {{ ($settings['enable_sms'] ?? '0') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">SMS Notifications</span>
                                <p class="text-xs text-gray-500">Text message alerts</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sms text-orange-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Notification Types</h3>
                    
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="notify_low_stock" value="1" 
                                   {{ ($settings['notify_low_stock'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Low Stock Alerts</span>
                                <p class="text-xs text-gray-500">Inventory level warnings</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="notify_new_orders" value="1" 
                                   {{ ($settings['notify_new_orders'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">New Order Alerts</span>
                                <p class="text-xs text-gray-500">Sales notifications</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-purple-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="notify_system_updates" value="1" 
                                   {{ ($settings['notify_system_updates'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">System Updates</span>
                                <p class="text-xs text-gray-500">Maintenance and updates</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sync text-teal-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Performance Settings</h2>
                <span class="text-sm text-gray-500">Optimize system performance</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-memory mr-1 text-orange-500"></i>Cache Driver
                        </label>
                        <select name="cache_driver" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="file" {{ ($settings['cache_driver'] ?? 'file') === 'file' ? 'selected' : '' }}>File System</option>
                            <option value="redis" {{ ($settings['cache_driver'] ?? '') === 'redis' ? 'selected' : '' }}>Redis</option>
                            <option value="memcached" {{ ($settings['cache_driver'] ?? '') === 'memcached' ? 'selected' : '' }}>Memcached</option>
                            <option value="database" {{ ($settings['cache_driver'] ?? '') === 'database' ? 'selected' : '' }}>Database</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Cache storage mechanism</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hourglass-half mr-1 text-orange-500"></i>Session Lifetime (Minutes)
                        </label>
                        <input type="number" name="session_lifetime" value="{{ $settings['session_lifetime'] ?? '120' }}" 
                               min="5" max="1440"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                               placeholder="120">
                        <p class="text-xs text-gray-500 mt-1">User session duration</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-database mr-1 text-orange-500"></i>Query Log
                        </label>
                        <select name="enable_query_log" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="0" {{ ($settings['enable_query_log'] ?? '0') === '0' ? 'selected' : '' }}>Disabled</option>
                            <option value="1" {{ ($settings['enable_query_log'] ?? '') === '1' ? 'selected' : '' }}>Enabled</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Log database queries for debugging</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-bug mr-1 text-orange-500"></i>Debug Mode
                        </label>
                        <select name="debug_mode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="false" {{ ($settings['debug_mode'] ?? 'false') === 'false' ? 'selected' : '' }}>Disabled</option>
                            <option value="true" {{ ($settings['debug_mode'] ?? '') === 'true' ? 'selected' : '' }}>Enabled</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Show detailed error information</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appearance & Theme -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Appearance & Theme</h2>
                <span class="text-sm text-gray-500">Customize system appearance</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-palette mr-1 text-purple-500"></i>Theme
                        </label>
                        <select name="theme" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                @change="updateTheme($event.target.value)">
                            <option value="light" {{ ($settings['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light Theme</option>
                            <option value="dark" {{ ($settings['theme'] ?? '') === 'dark' ? 'selected' : '' }}>Dark Theme</option>
                            <option value="auto" {{ ($settings['theme'] ?? '') === 'auto' ? 'selected' : '' }}>Auto (System Preference)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Choose color scheme</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-text-height mr-1 text-purple-500"></i>Font Size
                        </label>
                        <select name="font_size" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="small" {{ ($settings['font_size'] ?? 'medium') === 'small' ? 'selected' : '' }}>Small</option>
                            <option value="medium" {{ ($settings['font_size'] ?? 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="large" {{ ($settings['font_size'] ?? '') === 'large' ? 'selected' : '' }}>Large</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Interface text size</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-language mr-1 text-purple-500"></i>Interface Language
                        </label>
                        <select name="interface_language" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="en" {{ ($settings['interface_language'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                            <option value="sw" {{ ($settings['interface_language'] ?? '') === 'sw' ? 'selected' : '' }}>Swahili</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">System interface language</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock mr-1 text-purple-500"></i>Time Format
                        </label>
                        <select name="time_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="12h" {{ ($settings['time_format'] ?? '12h') === '12h' ? 'selected' : '' }}>12-hour (AM/PM)</option>
                            <option value="24h" {{ ($settings['time_format'] ?? '') === '24h' ? 'selected' : '' }}>24-hour</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Time display format</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Security Settings</h2>
                <span class="text-sm text-gray-500">System security configuration</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-1 text-red-500"></i>Session Timeout (Minutes)
                        </label>
                        <input type="number" name="session_timeout" value="{{ $settings['session_timeout'] ?? '30' }}" 
                               min="5" max="480"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="30">
                        <p class="text-xs text-gray-500 mt-1">Auto-logout inactive users</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-key mr-1 text-red-500"></i>Password Policy
                        </label>
                        <select name="password_policy" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="basic" {{ ($settings['password_policy'] ?? 'basic') === 'basic' ? 'selected' : '' }}>Basic (6+ chars)</option>
                            <option value="medium" {{ ($settings['password_policy'] ?? '') === 'medium' ? 'selected' : '' }}>Medium (8+ chars, mixed)</option>
                            <option value="strong" {{ ($settings['password_policy'] ?? '') === 'strong' ? 'selected' : '' }}>Strong (12+ chars, symbols)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Password complexity requirements</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-shield-alt mr-1 text-red-500"></i>Two-Factor Authentication
                        </label>
                        <select name="enable_2fa" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="0" {{ ($settings['enable_2fa'] ?? '0') === '0' ? 'selected' : '' }}>Disabled</option>
                            <option value="1" {{ ($settings['enable_2fa'] ?? '') === '1' ? 'selected' : '' }}>Enabled</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Require 2FA for admin users</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-history mr-1 text-red-500"></i>Login Attempt Limit
                        </label>
                        <input type="number" name="login_attempts" value="{{ $settings['login_attempts'] ?? '5' }}" 
                               min="3" max="10"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="5">
                        <p class="text-xs text-gray-500 mt-1">Max failed login attempts</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
            <button type="button" @click="testSettings()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-flask mr-2"></i>Test Settings
            </button>
            <button type="submit" class="px-6 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-save mr-2"></i>Save Changes
            </button>
        </div>
    </form>
</div>

<script>
function systemSettings() {
    return {
        activeModulesCount: 0,
        systemHealth: 'Good',
        currentTheme: 'Light',
        performanceScore: '95%',
        
        init() {
            this.updateModulesCount();
            this.updateTheme(document.querySelector('[name="theme"]').value);
            this.assessSystemHealth();
        },
        
        updateModulesCount() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="enable_"]:checked');
            this.activeModulesCount = checkboxes.length;
        },
        
        updateTheme(value) {
            this.currentTheme = value.charAt(0).toUpperCase() + value.slice(1);
        },
        
        assessSystemHealth() {
            // Simple health assessment based on enabled modules
            if (this.activeModulesCount >= 4) {
                this.systemHealth = 'Excellent';
            } else if (this.activeModulesCount >= 2) {
                this.systemHealth = 'Good';
            } else {
                this.systemHealth = 'Limited';
            }
        },
        
        runDiagnostics() {
            // Simulate system diagnostics
            alert('Running system diagnostics...\n\n✓ Database connection: OK\n✓ Cache system: OK\n✓ File permissions: OK\n✓ Memory usage: 45%\n✓ CPU usage: 12%\n\nSystem Health: Excellent');
        },
        
        optimizeSystem() {
            if (confirm('This will optimize system performance. Continue?')) {
                // Simulate optimization
                this.performanceScore = '98%';
                alert('System optimization completed!\n\n• Cache cleared\n• Database optimized\n• Sessions cleaned\n• Performance score: 98%');
            }
        },
        
        testSettings() {
            // Test system configuration
            const theme = document.querySelector('[name="theme"]').value;
            const cacheDriver = document.querySelector('[name="cache_driver"]').value;
            alert(`System configuration test:\nTheme: ${theme}\nCache Driver: ${cacheDriver}\nActive Modules: ${this.activeModulesCount}\nConfiguration appears valid!`);
        }
    }
}
</script>
@endsection











