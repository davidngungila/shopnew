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
            <button @click="runSystemDiagnostics()" class="px-4 py-2 text-white rounded-lg hover:bg-blue-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-stethoscope mr-2"></i>System Diagnostics
            </button>
            <button @click="scheduleBackup()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
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

    <!-- Advanced System Diagnostics -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm border border-blue-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-stethoscope mr-2 text-blue-600"></i>
                Advanced System Diagnostics
            </h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Last scan: <span x-text="lastDiagnosticTime">Never</span></span>
                <button @click="runSystemDiagnostics()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-play mr-2"></i>Run Full Diagnostics
                </button>
            </div>
        </div>
        
        <!-- Diagnostics Progress -->
        <div x-show="diagnosticRunning" class="mb-4">
            <div class="bg-white rounded-lg p-4 border border-blue-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Running Diagnostics...</span>
                    <span class="text-sm text-blue-600" x-text="diagnosticProgress + '%'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="`width: ${diagnosticProgress}%`"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2" x-text="currentDiagnosticTask"></p>
            </div>
        </div>
        
        <!-- Diagnostics Results -->
        <div x-show="diagnosticResults.length > 0" class="space-y-3">
            <template x-for="result in diagnosticResults" :key="result.category">
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3"
                                 :class="result.status === 'healthy' ? 'bg-green-100' : result.status === 'warning' ? 'bg-yellow-100' : 'bg-red-100'">
                                <i :class="result.icon" 
                                   :class="result.status === 'healthy' ? 'text-green-600' : result.status === 'warning' ? 'text-yellow-600' : 'text-red-600'"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900" x-text="result.category"></h4>
                                <p class="text-xs text-gray-500" x-text="result.description"></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-medium rounded-full"
                                  :class="result.status === 'healthy' ? 'bg-green-100 text-green-800' : result.status === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'"
                                  x-text="result.status.toUpperCase()"></span>
                            <div class="text-xs text-gray-500 mt-1" x-text="result.responseTime + 'ms'"></div>
                        </div>
                    </div>
                    <div x-show="result.details" class="mt-3 pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-600" x-text="result.details"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Quick Actions Dashboard -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-bolt mr-2 text-yellow-500"></i>
            Quick Actions Dashboard
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Full Backup -->
            <button @click="createFullBackup()" 
                    class="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105">
                <div class="relative z-10">
                    <i class="fas fa-database text-2xl mb-2"></i>
                    <h4 class="font-semibold">Full Backup</h4>
                    <p class="text-xs opacity-90 mt-1">Complete system backup</p>
                </div>
                <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-white opacity-10 rounded-full transform group-hover:scale-150 transition-transform duration-300"></div>
            </button>
            
            <!-- Database Backup -->
            <button @click="createDatabaseBackup()" 
                    class="group relative overflow-hidden bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105">
                <div class="relative z-10">
                    <i class="fas fa-server text-2xl mb-2"></i>
                    <h4 class="font-semibold">Database Backup</h4>
                    <p class="text-xs opacity-90 mt-1">Database only</p>
                </div>
                <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-white opacity-10 rounded-full transform group-hover:scale-150 transition-transform duration-300"></div>
            </button>
            
            <!-- Optimize System -->
            <button @click="optimizeSystem()" 
                    class="group relative overflow-hidden bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-4 text-white hover:from-purple-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                <div class="relative z-10">
                    <i class="fas fa-tachometer-alt text-2xl mb-2"></i>
                    <h4 class="font-semibold">Optimize System</h4>
                    <p class="text-xs opacity-90 mt-1">Clean & optimize</p>
                </div>
                <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-white opacity-10 rounded-full transform group-hover:scale-150 transition-transform duration-300"></div>
            </button>
            
            <!-- Clear Cache -->
            <button @click="clearSystemCache()" 
                    class="group relative overflow-hidden bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-4 text-white hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105">
                <div class="relative z-10">
                    <i class="fas fa-broom text-2xl mb-2"></i>
                    <h4 class="font-semibold">Clear Cache</h4>
                    <p class="text-xs opacity-90 mt-1">Clear system cache</p>
                </div>
                <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-white opacity-10 rounded-full transform group-hover:scale-150 transition-transform duration-300"></div>
            </button>
        </div>
        
        <!-- Action Status -->
        <div x-show="actionStatus.message" class="mt-4 p-3 rounded-lg" 
             :class="actionStatus.type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'">
            <div class="flex items-center">
                <i :class="actionStatus.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'" class="mr-2"></i>
                <span x-text="actionStatus.message"></span>
            </div>
        </div>
    </div>

    <!-- System Health Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">System Health</p>
                    <p class="text-2xl font-bold text-green-600" x-text="systemHealth">Excellent</p>
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
                    <p class="text-sm font-medium text-gray-500">Last Backup</p>
                    <p class="text-2xl font-bold text-blue-600" x-text="lastBackupStatus">Success</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-database text-blue-500"></i> 
                        <span x-text="lastBackupTime">2 hours ago</span>
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
                    <p class="text-sm font-medium text-gray-500">Storage Used</p>
                    <p class="text-2xl font-bold text-orange-600" x-text="storageUsed">45%</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-hdd text-orange-500"></i> 
                        <span x-text="storageDetails">2.3GB / 5GB</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-hdd text-orange-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Performance</p>
                    <p class="text-2xl font-bold text-purple-600" x-text="performanceScore">98%</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-tachometer-alt text-purple-500"></i> 
                        System speed
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-tachometer-alt text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
            <span class="text-sm text-gray-500">Common maintenance tasks</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <button @click="createBackup('full')" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-download text-blue-600"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-sm font-medium text-gray-900">Full Backup</h3>
                    <p class="text-xs text-gray-500">Complete system backup</p>
                </div>
            </button>

            <button @click="createBackup('database')" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-database text-green-600"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-sm font-medium text-gray-900">Database Backup</h3>
                    <p class="text-xs text-gray-500">Database only</p>
                </div>
            </button>

            <button @click="optimizeSystem()" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-magic text-orange-600"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-sm font-medium text-gray-900">Optimize System</h3>
                    <p class="text-xs text-gray-500">Clean & optimize</p>
                </div>
            </button>

            <button @click="clearCache()" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-broom text-purple-600"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-sm font-medium text-gray-900">Clear Cache</h3>
                    <p class="text-xs text-gray-500">Clear system cache</p>
                </div>
            </button>
        </div>
    </div>

    <!-- Backup Management -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Backup Management</h2>
            <div class="flex items-center space-x-4">
                <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>All Backups</option>
                    <option>Full Backups</option>
                    <option>Database Backups</option>
                    <option>File Backups</option>
                </select>
                <button @click="refreshBackups()" class="text-sm text-blue-600 hover:text-blue-800">
                    <i class="fas fa-sync-alt mr-1"></i>Refresh
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Backup</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-archive text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">shopsmart_backup_2024_03_10_12_30.sql</div>
                                    <div class="text-xs text-gray-500">ID: #BK001</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Full Backup
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">245.7 MB</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2 hours ago</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Complete
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <button @click="downloadBackup('BK001')" class="text-blue-600 hover:text-blue-900" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button @click="restoreBackup('BK001')" class="text-green-600 hover:text-green-900" title="Restore">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <button @click="deleteBackup('BK001')" class="text-red-600 hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-database text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">database_backup_2024_03_10_10_15.sql</div>
                                    <div class="text-xs text-gray-500">ID: #BK002</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Database Only
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">156.3 MB</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">4 hours ago</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Complete
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <button @click="downloadBackup('BK002')" class="text-blue-600 hover:text-blue-900" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button @click="restoreBackup('BK002')" class="text-green-600 hover:text-green-900" title="Restore">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <button @click="deleteBackup('BK002')" class="text-red-600 hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-folder text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">files_backup_2024_03_09_18_45.zip</div>
                                    <div class="text-xs text-gray-500">ID: #BK003</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                Files Only
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">89.2 MB</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yesterday</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Complete
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <button @click="downloadBackup('BK003')" class="text-blue-600 hover:text-blue-900" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button @click="restoreBackup('BK003')" class="text-green-600 hover:text-green-900" title="Restore">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <button @click="deleteBackup('BK003')" class="text-red-600 hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- System Maintenance -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">System Maintenance</h2>
            <span class="text-sm text-gray-500">Optimize and maintain system performance</span>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Database Maintenance -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h3 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-database text-blue-500 mr-2"></i>
                    Database Maintenance
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Optimize Database</p>
                            <p class="text-xs text-gray-500">Optimize tables and indexes</p>
                        </div>
                        <button @click="optimizeDatabase()" class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200 transition-colors">
                            Optimize
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Repair Database</p>
                            <p class="text-xs text-gray-500">Check and repair tables</p>
                        </div>
                        <button @click="repairDatabase()" class="px-3 py-2 bg-green-100 text-green-700 rounded-lg text-sm hover:bg-green-200 transition-colors">
                            Repair
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Clear Logs</p>
                            <p class="text-xs text-gray-500">Clear old system logs</p>
                        </div>
                        <button @click="clearLogs()" class="px-3 py-2 bg-orange-100 text-orange-700 rounded-lg text-sm hover:bg-orange-200 transition-colors">
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- File System Maintenance -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h3 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-folder text-green-500 mr-2"></i>
                    File System Maintenance
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Clean Temp Files</p>
                            <p class="text-xs text-gray-500">Remove temporary files</p>
                        </div>
                        <button @click="cleanTempFiles()" class="px-3 py-2 bg-green-100 text-green-700 rounded-lg text-sm hover:bg-green-200 transition-colors">
                            Clean
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Compress Old Files</p>
                            <p class="text-xs text-gray-500">Compress old backup files</p>
                        </div>
                        <button @click="compressFiles()" class="px-3 py-2 bg-purple-100 text-purple-700 rounded-lg text-sm hover:bg-purple-200 transition-colors">
                            Compress
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Check Disk Space</p>
                            <p class="text-xs text-gray-500">Analyze disk usage</p>
                        </div>
                        <button @click="checkDiskSpace()" class="px-3 py-2 bg-orange-100 text-orange-700 rounded-lg text-sm hover:bg-orange-200 transition-colors">
                            Check
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Automated Backup Settings -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Automated Backup Settings</h2>
            <span class="text-sm text-gray-500">Configure automatic backup schedules</span>
        </div>
        
        <form action="{{ route('settings.backup.schedule') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-1 text-blue-500"></i>Backup Frequency
                    </label>
                    <select name="backup_frequency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="disabled">Disabled</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">How often to create automatic backups</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-archive mr-1 text-green-500"></i>Backup Type
                    </label>
                    <select name="backup_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="full">Full Backup</option>
                        <option value="database">Database Only</option>
                        <option value="files">Files Only</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Type of automatic backup</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1 text-purple-500"></i>Retention Period
                    </label>
                    <select name="retention_period" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="7">7 Days</option>
                        <option value="14">14 Days</option>
                        <option value="30">30 Days</option>
                        <option value="90">90 Days</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">How long to keep backup files</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1 text-orange-500"></i>Email Notifications
                    </label>
                    <input type="email" name="notification_email" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="admin@example.com">
                    <p class="text-xs text-gray-500 mt-1">Receive backup status notifications</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center space-x-3">
                    <input type="checkbox" name="enable_compression" value="1" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Enable Backup Compression</span>
                        <p class="text-xs text-gray-500">Reduce backup file size</p>
                    </div>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-compress text-blue-600"></i>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                    <i class="fas fa-save mr-2"></i>Save Schedule
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function backupSystem() {
    return {
        systemHealth: 'Excellent',
        lastBackupStatus: 'Success',
        lastBackupTime: '2 hours ago',
        storageUsed: '45%',
        storageDetails: '2.3GB / 5GB',
        performanceScore: '98%',
        
        init() {
            this.updateSystemStatus();
        },
        
        updateSystemStatus() {
            // Simulate real-time system status updates
            setInterval(() => {
                this.performanceScore = Math.floor(Math.random() * 5) + 95 + '%';
            }, 30000);
        },
        
        runSystemDiagnostics() {
            alert('Running comprehensive system diagnostics...\n\n✓ Database integrity: PASSED\n✓ File system: HEALTHY\n✓ Memory usage: 42%\n✓ CPU usage: 12%\n✓ Disk space: 45% used\n✓ Network connectivity: STABLE\n✓ Security status: SECURE\n\nOverall System Health: EXCELLENT');
        },
        
        createBackup(type) {
            const confirmMessage = `Create ${type} backup?\n\nThis will backup:\n${type === 'full' ? '• Database\n• Application files\n• Configuration files\n• User uploads' : type === 'database' ? '• All database tables\n• Stored procedures\n• Database schema' : '• Application files\n• User uploads\n• Configuration files'}`;
            
            const diagnosticTasks = [
                { category: 'Database Connection', icon: 'fas fa-database', task: 'Checking database connectivity...' },
                { category: 'Cache System', icon: 'fas fa-memory', task: 'Analyzing cache performance...' },
                { category: 'File System', icon: 'fas fa-folder', task: 'Scanning file system integrity...' },
                { category: 'API Endpoints', icon: 'fas fa-plug', task: 'Testing API endpoints...' },
                { category: 'Security Settings', icon: 'fas fa-shield-alt', task: 'Validating security configuration...' },
                { category: 'Performance Metrics', icon: 'fas fa-tachometer-alt', task: 'Measuring system performance...' },
                { category: 'Storage Health', icon: 'fas fa-hdd', task: 'Checking storage availability...' },
                { category: 'Network Connectivity', icon: 'fas fa-network-wired', task: 'Testing network connections...' }
            ];
            
            for (let i = 0; i < diagnosticTasks.length; i++) {
                const task = diagnosticTasks[i];
                this.currentDiagnosticTask = task.task;
                this.diagnosticProgress = Math.round(((i + 1) / diagnosticTasks.length) * 100);
                
                // Simulate diagnostic check
                await new Promise(resolve => setTimeout(resolve, 800));
                
                // Generate random results for demonstration
                const status = Math.random() > 0.2 ? 'healthy' : Math.random() > 0.5 ? 'warning' : 'critical';
                const responseTime = Math.floor(Math.random() * 200) + 50;
                
                let details = '';
                if (status === 'healthy') {
                    details = `All systems operational. Response time within acceptable range.`;
                } else if (status === 'warning') {
                    details = `Minor issues detected. System performance may be slightly degraded.`;
                } else {
                    details = `Critical issues found. Immediate attention required.`;
                }
                
                this.diagnosticResults.push({
                    category: task.category,
                    icon: task.icon,
                    description: `Health check for ${task.category.toLowerCase()}`,
                    status: status,
                    responseTime: responseTime,
                    details: details
                });
            }
            
            this.diagnosticRunning = false;
            this.lastDiagnosticTime = new Date().toLocaleTimeString();
            
            // Show summary
            const healthyCount = this.diagnosticResults.filter(r => r.status === 'healthy').length;
            const warningCount = this.diagnosticResults.filter(r => r.status === 'warning').length;
            const criticalCount = this.diagnosticResults.filter(r => r.status === 'critical').length;
            
            this.showActionStatus('success', `Diagnostics completed: ${healthyCount} healthy, ${warningCount} warnings, ${criticalCount} critical issues`);
        },
        
        async createFullBackup() {
            this.showActionStatus('success', 'Starting full system backup...');
            
            // Simulate backup process
            await new Promise(resolve => setTimeout(resolve, 2000));
            
            this.showActionStatus('success', 'Full backup completed successfully! Backup ID: BK-' + Date.now());
        },
        
        async createDatabaseBackup() {
            this.showActionStatus('success', 'Starting database backup...');
            
            // Simulate backup process
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            this.showActionStatus('success', 'Database backup completed! Size: 245MB');
        },
        
        async optimizeSystem() {
            this.showActionStatus('success', 'Starting system optimization...');
            
            const optimizationSteps = [
                'Clearing application cache...',
                'Optimizing database tables...',
                'Compiling views...',
                'Caching routes...',
                'Optimizing configuration...'
            ];
            
            for (const step of optimizationSteps) {
                await new Promise(resolve => setTimeout(resolve, 500));
                this.showActionStatus('success', step);
            }
            
            this.showActionStatus('success', 'System optimization completed! Performance improved by 23%');
        },
        
        async clearSystemCache() {
            this.showActionStatus('success', 'Clearing system cache...');
            
            const cacheTypes = [
                'Application cache',
                'View cache',
                'Route cache',
                'Configuration cache',
                'Session cache'
            ];
            
            for (const cacheType of cacheTypes) {
                await new Promise(resolve => setTimeout(resolve, 300));
                this.showActionStatus('success', `Cleared ${cacheType}`);
            }
            
            this.showActionStatus('success', 'All system caches cleared successfully! 1.2GB freed');
        },
        
        showActionStatus(type, message) {
            this.actionStatus = { type, message };
            setTimeout(() => {
                this.actionStatus = { type: '', message: '' };
            }, 5000);
        },
        
        scheduleBackup() {
            alert('Backup scheduling interface would open here\n\nOptions:\n• Daily backups at 2:00 AM\n• Weekly backups on Sundays\n• Monthly backups on 1st day\n• Custom schedule\n\nThis would integrate with the backup scheduling system.');
        },
        
        createBackup() {
            alert('Creating manual backup...\n\nThis would:\n• Create a full system backup\n• Include database and files\n• Generate backup report\n• Store in configured location\n\nBackup ID: BK-' + Date.now());
        },
        
        clearCache() {
            alert('Clearing system cache...\n\nThis would clear:\n• Application cache\n• View cache\n• Route cache\n• Configuration cache\n\nCache cleared successfully!');
        },
        
        optimizeDb() {
            alert('Optimizing database...\n\nThis would:\n• Optimize database tables\n• Rebuild indexes\n• Clean up orphaned records\n• Update statistics\n\nDatabase optimized successfully!');
        },
        
        clearAll() {
            if (confirm('Are you sure you want to clear all caches and optimize the system? This action cannot be undone.')) {
                alert('System maintenance completed!\n\n• All caches cleared\n• Database optimized\n• System performance improved\n• 2.1GB storage freed');
            }
        },
        
        createBackup(type) {
            const confirmMessage = `Create ${type} backup?\n\nThis will backup:\n${type === 'full' ? '• Database\n• Application files\n• Configuration files\n• User uploads' : type === 'database' ? '• All database tables\n• Stored procedures\n• Database schema' : '• Application files\n• User uploads\n• Configuration files'}`;
            
            if (confirm(confirmMessage)) {
                alert(`Creating ${type} backup...\n\nEstimated time: 2-5 minutes\nYou will be notified when complete.`);
            }
        },
        
        runSystemDiagnostics() {
            alert('Running comprehensive system diagnostics...\n\n✓ Database integrity: PASSED\n✓ File system: HEALTHY\n✓ Memory usage: 42%\n✓ CPU usage: 12%\n✓ Disk space: 45% used\n✓ Network connectivity: STABLE\n✓ Security status: SECURE\n\nOverall System Health: EXCELLENT');
        },
        
        optimizeDatabase() {
            alert('Optimizing database...\n\n✓ Tables optimized\n✓ Indexes rebuilt\n✓ Query performance improved\n\nDatabase optimization complete!');
        },
        
        repairDatabase() {
            if (confirm('Repair database?\n\nThis will check and repair all tables.')) {
                alert('Repairing database...\n\n✓ Checking table integrity\n✓ Repairing corrupted data\n✓ Optimizing storage\n\nDatabase repair complete!');
            }
        },
        
        downloadBackup(backupId) {
            alert(`Downloading backup ${backupId}...\n\nFile size: 245.7 MB\nDownload will start shortly.`);
        },
        
        restoreBackup(backupId) {
            if (confirm(`Restore backup ${backupId}?\n\n⚠️ WARNING: This will overwrite current data!\n\nCurrent data will be lost.`)) {
                alert(`Restoring backup ${backupId}...\n\nEstimated time: 5-10 minutes\nSystem will restart after restore.`);
            }
        },
        
        deleteBackup(backupId) {
            if (confirm(`Delete backup ${backupId}?\n\nThis action cannot be undone.`)) {
                alert(`Backup ${backupId} deleted successfully.`);
            }
        },
        
        refreshBackups() {
            alert('Refreshing backup list...\n\nLatest backups loaded successfully.');
        },
        
        cleanTempFiles() {
            alert('Cleaning temporary files...\n\n✓ Session files cleared\n✓ Cache files cleared\n✓ Upload temp files cleared\n\n156 MB of temporary files removed!');
        },
        
        compressFiles() {
            alert('Compressing old files...\n\n✓ Old backups compressed\n✓ Log files compressed\n✓ Archive files created\n\nStorage space saved: 324 MB');
        },
        
        checkDiskSpace() {
            alert('Disk space analysis:\n\nTotal Space: 5.0 GB\nUsed Space: 2.3 GB (45%)\nFree Space: 2.7 GB (55%)\n\nLargest files:\n1. shopsmart_backup_2024_03_10.sql (245.7 MB)\n2. database_backup_2024_03_09.sql (156.3 MB)\n3. files_backup_2024_03_08.zip (89.2 MB)');
        }
    }
}
</script>
@endsection
