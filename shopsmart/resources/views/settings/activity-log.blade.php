@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="space-y-6" x-data="activityLog()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Activity Log</h1>
            <p class="text-gray-600 mt-1">Monitor and track all system activities</p>
        </div>
        <div class="flex gap-2">
            <button @click="exportActivityLog()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-download mr-2"></i>Export
            </button>
            <button @click="clearActivityLog()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                <i class="fas fa-trash mr-2"></i>Clear Log
            </button>
            <a href="{{ route('settings.users') }}" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors" style="background-color: #6b7280;">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
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

    <!-- Activity Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Activities</p>
                    <p class="text-2xl font-bold text-blue-600" x-text="totalActivities">1,247</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-chart-line text-blue-500"></i> 
                        All time records
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Today's Activities</p>
                    <p class="text-2xl font-bold text-green-600" x-text="todayActivities">47</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-calendar-day text-green-500"></i> 
                        Last 24 hours
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-day text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Failed Logins</p>
                    <p class="text-2xl font-bold text-red-600" x-text="failedLogins">12</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-exclamation-triangle text-red-500"></i> 
                        Security alerts
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Users</p>
                    <p class="text-2xl font-bold text-purple-600" x-text="activeUsers">8</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-users text-purple-500"></i> 
                        Currently online
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" x-model="search" @input="filterActivities()" 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="Search activities by user, action, or IP...">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            <select x-model="actionFilter" @change="filterActivities()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Actions</option>
                <option value="login">Login</option>
                <option value="logout">Logout</option>
                <option value="create">Create</option>
                <option value="update">Update</option>
                <option value="delete">Delete</option>
                <option value="failed_login">Failed Login</option>
                <option value="password_change">Password Change</option>
                <option value="role_change">Role Change</option>
            </select>
            <select x-model="userFilter" @change="filterActivities()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Users</option>
                <option value="admin">Admin</option>
                <option value="manager">Manager</option>
                <option value="cashier">Cashier</option>
                <option value="sales">Sales</option>
            </select>
            <select x-model="dateFilter" @change="filterActivities()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
            </select>
        </div>
    </div>

    <!-- Activity Log Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">Showing <span x-text="filteredCount">247</span> activities</span>
                <button @click="refreshActivities()" class="text-sm text-green-600 hover:text-green-800">
                    <i class="fas fa-sync-alt mr-1"></i>Refresh
                </button>
            </div>
            <div class="flex items-center space-x-2">
                <button @click="toggleAutoRefresh()" class="px-3 py-1 text-sm rounded-lg" 
                        :class="autoRefresh ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'">
                    <i class="fas fa-sync-alt mr-1"></i>
                    <span x-text="autoRefresh ? 'Auto-refresh ON' : 'Auto-refresh OFF'"></span>
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span>Timestamp</span>
                                <button @click="sortBy('timestamp')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="activity in filteredActivities" :key="activity.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div x-text="activity.timestamp"></div>
                                    <div class="text-xs text-gray-500" x-text="activity.relativeTime"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center mr-3">
                                        <span class="text-white font-semibold text-xs" x-text="activity.user.charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900" x-text="activity.user"></div>
                                        <div class="text-xs text-gray-500" x-text="activity.role"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      :class="getActionClass(activity.action)">
                                    <i :class="getActionIcon(activity.action)" class="mr-1"></i>
                                    <span x-text="activity.actionFormatted"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <div x-text="activity.details"></div>
                                    <div x-show="activity.additionalInfo" class="text-xs text-gray-500 mt-1" x-text="activity.additionalInfo"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div x-text="activity.ipAddress"></div>
                                    <div x-show="activity.location" class="text-xs text-gray-500" x-text="activity.location"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      :class="getStatusClass(activity.status)">
                                    <i :class="getStatusIcon(activity.status)" class="mr-1"></i>
                                    <span x-text="activity.statusFormatted"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <button @click="viewActivityDetails(activity)" class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button @click="exportActivity(activity)" class="text-green-600 hover:text-green-900" title="Export Activity">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button x-show="activity.action === 'failed_login'" @click="blockIP(activity.ipAddress)" class="text-red-600 hover:text-red-900" title="Block IP">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button @click="previousPage()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </button>
                <button @click="nextPage()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium" x-text="startItem">1</span>
                        to
                        <span class="font-medium" x-text="endItem">20</span>
                        of
                        <span class="font-medium" x-text="totalCount">247</span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button @click="previousPage()" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            <span x-text="currentPage">1</span>
                        </span>
                        <button @click="nextPage()" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Details Modal -->
    <div x-show="showDetailsModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="details-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDetailsModal = false"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="details-modal-title">Activity Details</h3>
                            <div class="mt-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-900 mb-3">Basic Information</h5>
                                        <dl class="space-y-2">
                                            <div class="flex justify-between">
                                                <dt class="text-sm text-gray-500">Timestamp</dt>
                                                <dd class="text-sm text-gray-900" x-text="selectedActivity.timestamp"></dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm text-gray-500">User</dt>
                                                <dd class="text-sm text-gray-900" x-text="selectedActivity.user"></dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm text-gray-500">Action</dt>
                                                <dd class="text-sm text-gray-900" x-text="selectedActivity.actionFormatted"></dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm text-gray-500">Status</dt>
                                                <dd class="text-sm text-gray-900">
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                          :class="getStatusClass(selectedActivity.status)">
                                                        <span x-text="selectedActivity.statusFormatted"></span>
                                                    </span>
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>
                                    
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-900 mb-3">Technical Details</h5>
                                        <dl class="space-y-2">
                                            <div class="flex justify-between">
                                                <dt class="text-sm text-gray-500">IP Address</dt>
                                                <dd class="text-sm text-gray-900" x-text="selectedActivity.ipAddress"></dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm text-gray-500">User Agent</dt>
                                                <dd class="text-sm text-gray-900 truncate" x-text="selectedActivity.userAgent"></dd>
                                            </div>
                                            <div x-show="selectedActivity.location" class="flex justify-between">
                                                <dt class="text-sm text-gray-500">Location</dt>
                                                <dd class="text-sm text-gray-900" x-text="selectedActivity.location"></dd>
                                            </div>
                                            <div x-show="selectedActivity.device" class="flex justify-between">
                                                <dt class="text-sm text-gray-500">Device</dt>
                                                <dd class="text-sm text-gray-900" x-text="selectedActivity.device"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <h5 class="text-sm font-medium text-gray-900 mb-3">Activity Details</h5>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-900" x-text="selectedActivity.details"></p>
                                        <div x-show="selectedActivity.additionalInfo" class="mt-2 text-sm text-gray-600" x-text="selectedActivity.additionalInfo"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showDetailsModal = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function activityLog() {
    return {
        search: '',
        actionFilter: '',
        userFilter: '',
        dateFilter: '',
        showDetailsModal: false,
        selectedActivity: {},
        autoRefresh: false,
        refreshInterval: null,
        currentPage: 1,
        itemsPerPage: 20,
        totalActivities: 1247,
        todayActivities: 47,
        failedLogins: 12,
        activeUsers: 8,
        
        // Sample activity data
        activities: [
            {
                id: 1,
                timestamp: '2024-03-10 14:30:25',
                relativeTime: '2 hours ago',
                user: 'John Smith',
                role: 'Admin',
                action: 'login',
                actionFormatted: 'User Login',
                details: 'Successful login from web dashboard',
                additionalInfo: 'Chrome 145.0.0.0 on Windows 10',
                ipAddress: '192.168.1.100',
                location: 'Nairobi, Kenya',
                device: 'Desktop',
                userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                status: 'success',
                statusFormatted: 'Success'
            },
            {
                id: 2,
                timestamp: '2024-03-10 14:25:12',
                relativeTime: '2 hours ago',
                user: 'Jane Doe',
                role: 'Manager',
                action: 'create',
                actionFormatted: 'Created User',
                details: 'Created new user account for Michael Johnson',
                additionalInfo: 'User ID: 156, Role: Cashier',
                ipAddress: '192.168.1.105',
                location: 'Nairobi, Kenya',
                device: 'Desktop',
                userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                status: 'success',
                statusFormatted: 'Success'
            },
            {
                id: 3,
                timestamp: '2024-03-10 14:20:45',
                relativeTime: '2 hours ago',
                user: 'Unknown',
                role: 'Guest',
                action: 'failed_login',
                actionFormatted: 'Failed Login',
                details: 'Failed login attempt with invalid credentials',
                additionalInfo: 'Email: test@example.com, Reason: Invalid password',
                ipAddress: '192.168.1.200',
                location: 'Unknown',
                device: 'Desktop',
                userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                status: 'failed',
                statusFormatted: 'Failed'
            },
            {
                id: 4,
                timestamp: '2024-03-10 14:15:30',
                relativeTime: '2 hours ago',
                user: 'Bob Wilson',
                role: 'Cashier',
                action: 'update',
                actionFormatted: 'Updated Profile',
                details: 'Updated user profile information',
                additionalInfo: 'Changed: Phone number, Address',
                ipAddress: '192.168.1.150',
                location: 'Nairobi, Kenya',
                device: 'Mobile',
                userAgent: 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X)',
                status: 'success',
                statusFormatted: 'Success'
            },
            {
                id: 5,
                timestamp: '2024-03-10 14:10:18',
                relativeTime: '2 hours ago',
                user: 'Alice Brown',
                role: 'Sales',
                action: 'delete',
                actionFormatted: 'Deleted Record',
                details: 'Deleted sales record #1234',
                additionalInfo: 'Record: Sale #1234, Amount: $1,250.00',
                ipAddress: '192.168.1.175',
                location: 'Nairobi, Kenya',
                device: 'Desktop',
                userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                status: 'success',
                statusFormatted: 'Success'
            },
            {
                id: 6,
                timestamp: '2024-03-10 14:05:22',
                relativeTime: '2 hours ago',
                user: 'Charlie Davis',
                role: 'Admin',
                action: 'password_change',
                actionFormatted: 'Password Changed',
                details: 'User changed their password',
                additionalInfo: 'Password reset via email verification',
                ipAddress: '192.168.1.125',
                location: 'Nairobi, Kenya',
                device: 'Desktop',
                userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                status: 'success',
                statusFormatted: 'Success'
            },
            {
                id: 7,
                timestamp: '2024-03-10 13:55:40',
                relativeTime: '3 hours ago',
                user: 'Eva Martinez',
                role: 'Manager',
                action: 'role_change',
                actionFormatted: 'Role Changed',
                details: 'User role was changed from Cashier to Manager',
                additionalInfo: 'Previous Role: Cashier, New Role: Manager, Changed by: Admin',
                ipAddress: '192.168.1.130',
                location: 'Nairobi, Kenya',
                device: 'Desktop',
                userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                status: 'success',
                statusFormatted: 'Success'
            },
            {
                id: 8,
                timestamp: '2024-03-10 13:45:15',
                relativeTime: '3 hours ago',
                user: 'Frank Miller',
                role: 'Cashier',
                action: 'logout',
                actionFormatted: 'User Logout',
                details: 'User logged out from system',
                additionalInfo: 'Session duration: 2 hours 30 minutes',
                ipAddress: '192.168.1.160',
                location: 'Nairobi, Kenya',
                device: 'Desktop',
                userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                status: 'success',
                statusFormatted: 'Success'
            }
        ],
        
        get filteredActivities() {
            let filtered = this.activities;
            
            // Apply search filter
            if (this.search) {
                filtered = filtered.filter(activity => 
                    activity.user.toLowerCase().includes(this.search.toLowerCase()) ||
                    activity.actionFormatted.toLowerCase().includes(this.search.toLowerCase()) ||
                    activity.ipAddress.includes(this.search) ||
                    activity.details.toLowerCase().includes(this.search.toLowerCase())
                );
            }
            
            // Apply action filter
            if (this.actionFilter) {
                filtered = filtered.filter(activity => activity.action === this.actionFilter);
            }
            
            // Apply user filter
            if (this.userFilter) {
                filtered = filtered.filter(activity => activity.role.toLowerCase() === this.userFilter.toLowerCase());
            }
            
            // Apply date filter
            if (this.dateFilter) {
                const now = new Date();
                filtered = filtered.filter(activity => {
                    const activityDate = new Date(activity.timestamp);
                    switch(this.dateFilter) {
                        case 'today':
                            return activityDate.toDateString() === now.toDateString();
                        case 'week':
                            const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                            return activityDate >= weekAgo;
                        case 'month':
                            const monthAgo = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
                            return activityDate >= monthAgo;
                        case 'year':
                            const yearAgo = new Date(now.getTime() - 365 * 24 * 60 * 60 * 1000);
                            return activityDate >= yearAgo;
                        default:
                            return true;
                    }
                });
            }
            
            return filtered;
        },
        
        get filteredCount() {
            return this.filteredActivities.length;
        },
        
        get startItem() {
            return (this.currentPage - 1) * this.itemsPerPage + 1;
        },
        
        get endItem() {
            return Math.min(this.currentPage * this.itemsPerPage, this.filteredCount);
        },
        
        get totalCount() {
            return this.filteredCount;
        },
        
        init() {
            this.updateStatistics();
        },
        
        updateStatistics() {
            // Update statistics based on filtered activities
            const now = new Date();
            const today = now.toDateString();
            
            this.todayActivities = this.activities.filter(activity => 
                new Date(activity.timestamp).toDateString() === today
            ).length;
            
            this.failedLogins = this.activities.filter(activity => 
                activity.action === 'failed_login'
            ).length;
        },
        
        filterActivities() {
            this.currentPage = 1;
            this.updateStatistics();
        },
        
        sortBy(field) {
            console.log('Sorting by:', field);
            // In a real application, this would sort the activities
        },
        
        getActionClass(action) {
            const classes = {
                'login': 'bg-blue-100 text-blue-800',
                'logout': 'bg-gray-100 text-gray-800',
                'create': 'bg-green-100 text-green-800',
                'update': 'bg-yellow-100 text-yellow-800',
                'delete': 'bg-red-100 text-red-800',
                'failed_login': 'bg-red-100 text-red-800',
                'password_change': 'bg-purple-100 text-purple-800',
                'role_change': 'bg-indigo-100 text-indigo-800'
            };
            return classes[action] || 'bg-gray-100 text-gray-800';
        },
        
        getActionIcon(action) {
            const icons = {
                'login': 'fas fa-sign-in-alt',
                'logout': 'fas fa-sign-out-alt',
                'create': 'fas fa-plus',
                'update': 'fas fa-edit',
                'delete': 'fas fa-trash',
                'failed_login': 'fas fa-exclamation-triangle',
                'password_change': 'fas fa-key',
                'role_change': 'fas fa-user-tag'
            };
            return icons[action] || 'fas fa-info-circle';
        },
        
        getStatusClass(status) {
            const classes = {
                'success': 'bg-green-100 text-green-800',
                'failed': 'bg-red-100 text-red-800',
                'warning': 'bg-yellow-100 text-yellow-800',
                'pending': 'bg-blue-100 text-blue-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },
        
        getStatusIcon(status) {
            const icons = {
                'success': 'fas fa-check-circle',
                'failed': 'fas fa-times-circle',
                'warning': 'fas fa-exclamation-triangle',
                'pending': 'fas fa-clock'
            };
            return icons[status] || 'fas fa-question-circle';
        },
        
        viewActivityDetails(activity) {
            this.selectedActivity = activity;
            this.showDetailsModal = true;
        },
        
        exportActivity(activity) {
            alert(`Exporting activity #${activity.id}\n\nUser: ${activity.user}\nAction: ${activity.actionFormatted}\nTimestamp: ${activity.timestamp}\n\nThis would download a detailed report of this activity.`);
        },
        
        exportActivityLog() {
            const count = this.filteredCount;
            alert(`Exporting activity log\n\nTotal records: ${count}\nFormat: CSV\nIncludes: All filtered activities\n\nThis would download a comprehensive activity report.`);
        },
        
        clearActivityLog() {
            if (confirm('Are you sure you want to clear the activity log? This action cannot be undone and will permanently delete all activity records.')) {
                alert(`Activity log cleared successfully!\n\n${this.filteredCount} records deleted.\nThis functionality requires backend implementation.`);
            }
        },
        
        refreshActivities() {
            // Simulate refresh
            alert('Refreshing activity log...\n\nThis would fetch the latest activities from the server.');
            this.updateStatistics();
        },
        
        toggleAutoRefresh() {
            this.autoRefresh = !this.autoRefresh;
            
            if (this.autoRefresh) {
                this.refreshInterval = setInterval(() => {
                    this.refreshActivities();
                }, 30000); // Refresh every 30 seconds
                alert('Auto-refresh enabled (every 30 seconds)');
            } else {
                if (this.refreshInterval) {
                    clearInterval(this.refreshInterval);
                    this.refreshInterval = null;
                }
                alert('Auto-refresh disabled');
            }
        },
        
        blockIP(ipAddress) {
            if (confirm(`Are you sure you want to block IP address ${ipAddress}? This will prevent all future access from this IP address.`)) {
                alert(`IP address ${ipAddress} blocked successfully!\n\nThis functionality requires backend implementation.`);
            }
        },
        
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        
        nextPage() {
            const maxPage = Math.ceil(this.filteredCount / this.itemsPerPage);
            if (this.currentPage < maxPage) {
                this.currentPage++;
            }
        }
    }
}
</script>
@endsection
