@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<div class="space-y-6" x-data="rolesPermissions()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Roles & Permissions</h1>
            <p class="text-gray-600 mt-1">Manage user roles and access permissions</p>
        </div>
        <div class="flex gap-2">
            <button @click="createNewRole()" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2 hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-plus"></i>
                <span>New Role</span>
            </button>
            <button @click="exportRoles()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-download mr-2"></i>Export
            </button>
            <a href="{{ route('settings.users') }}" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors" style="background-color: #6b7280;">
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

    <!-- Roles Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Roles</p>
                    <p class="text-2xl font-bold text-blue-600">5</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-user-shield text-blue-500"></i> 
                        Defined roles
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
                    <p class="text-sm font-medium text-gray-500">Active Users</p>
                    <p class="text-2xl font-bold text-green-600">{{ App\Models\User::count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-users text-green-500"></i> 
                        Total users
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Admin Users</p>
                    <p class="text-2xl font-bold text-purple-600">{{ App\Models\User::where('role', 'admin')->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-crown text-purple-500"></i> 
                        Administrators
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-crown text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Custom Roles</p>
                    <p class="text-2xl font-bold text-orange-600">2</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-cog text-orange-500"></i> 
                        Custom defined
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-cog text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Grid -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">System Roles</h2>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search roles..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option>All Roles</option>
                    <option>System Roles</option>
                    <option>Custom Roles</option>
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Admin Role -->
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Administrator</h3>
                                <p class="text-xs text-purple-200">System Owner</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs">System</span>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600 mb-4">Full system access with all permissions</p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">All modules access</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">User management</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Settings management</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Reports & Analytics</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">System configuration</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                        <span><i class="fas fa-users mr-1"></i>{{ App\Models\User::where('role', 'admin')->count() }} users</span>
                        <span><i class="fas fa-shield-alt mr-1"></i>Full Access</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="viewRoleDetails('admin')" class="flex-1 px-3 py-2 bg-purple-100 text-purple-700 rounded-lg text-xs hover:bg-purple-200 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button @click="editRole('admin')" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Manager Role -->
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Manager</h3>
                                <p class="text-xs text-blue-200">Management Access</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs">System</span>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600 mb-4">Management access with limited permissions</p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Sales & POS</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Inventory management</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Reports access</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Customer management</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-gray-700">User management</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                        <span><i class="fas fa-users mr-1"></i>{{ App\Models\User::where('role', 'manager')->count() }} users</span>
                        <span><i class="fas fa-shield-alt mr-1"></i>High Access</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="viewRoleDetails('manager')" class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-xs hover:bg-blue-200 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button @click="editRole('manager')" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cashier Role -->
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Cashier</h3>
                                <p class="text-xs text-green-200">Sales Operations</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs">System</span>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600 mb-4">Sales operations with basic permissions</p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">POS access</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Sales creation</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Customer management</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-gray-700">Inventory management</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-gray-700">Reports access</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                        <span><i class="fas fa-users mr-1"></i>{{ App\Models\User::where('role', 'cashier')->count() }} users</span>
                        <span><i class="fas fa-shield-alt mr-1"></i>Medium Access</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="viewRoleDetails('cashier')" class="flex-1 px-3 py-2 bg-green-100 text-green-700 rounded-lg text-xs hover:bg-green-200 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button @click="editRole('cashier')" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Staff Role -->
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Staff</h3>
                                <p class="text-xs text-orange-200">Limited Access</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs">System</span>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600 mb-4">Limited access for basic operations</p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">View products</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">View sales</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-gray-700">Create sales</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-gray-700">Settings access</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-gray-700">User management</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                        <span><i class="fas fa-users mr-1"></i>{{ App\Models\User::where('role', 'staff')->count() }} users</span>
                        <span><i class="fas fa-shield-alt mr-1"></i>Low Access</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="viewRoleDetails('staff')" class="flex-1 px-3 py-2 bg-orange-100 text-orange-700 rounded-lg text-xs hover:bg-orange-200 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button @click="editRole('staff')" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Supervisor Role -->
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Supervisor</h3>
                                <p class="text-xs text-teal-200">Supervisory Access</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs">Custom</span>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600 mb-4">Supervisory access with monitoring permissions</p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Monitor sales</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">View reports</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Staff management</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-gray-700">System settings</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-gray-700">User creation</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                        <span><i class="fas fa-users mr-1"></i>{{ App\Models\User::where('role', 'supervisor')->count() }} users</span>
                        <span><i class="fas fa-shield-alt mr-1"></i>Medium Access</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="viewRoleDetails('supervisor')" class="flex-1 px-3 py-2 bg-teal-100 text-teal-700 rounded-lg text-xs hover:bg-teal-200 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button @click="editRole('supervisor')" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Auditor Role -->
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Auditor</h3>
                                <p class="text-xs text-indigo-200">Audit Access</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs">Custom</span>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600 mb-4">Audit access with read-only permissions</p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">View all data</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Generate reports</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700">Audit logs</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-gray-700">Modify data</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-gray-700">System settings</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                        <span><i class="fas fa-users mr-1"></i>{{ App\Models\User::where('role', 'auditor')->count() }} users</span>
                        <span><i class="fas fa-shield-alt mr-1"></i>Read-Only</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="viewRoleDetails('auditor')" class="flex-1 px-3 py-2 bg-indigo-100 text-indigo-700 rounded-lg text-xs hover:bg-indigo-200 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button @click="editRole('auditor')" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permission Matrix -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Permission Matrix</h2>
            <button @click="toggleMatrixView()" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                <i class="fas fa-th mr-2"></i>Toggle View
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permission</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Manager</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cashier</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Supervisor</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Auditor</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Dashboard Access</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">POS/Sales</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-eye text-blue-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-eye text-blue-500"></i>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Inventory Management</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-eye text-blue-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-eye text-blue-500"></i>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">User Management</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Reports & Analytics</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">System Settings</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function rolesPermissions() {
    return {
        selectedRole: null,
        matrixView: 'grid',
        
        init() {
            // Initialize component
        },
        
        createNewRole() {
            // Create new role functionality
            alert('Create new role dialog would open here with:\n\n• Role name\n• Role description\n• Permission checkboxes\n• User assignment options');
        },
        
        viewRoleDetails(roleName) {
            // View role details
            const roleDetails = {
                admin: {
                    name: 'Administrator',
                    description: 'Full system access with all permissions',
                    permissions: ['All modules access', 'User management', 'Settings management', 'Reports & Analytics', 'System configuration'],
                    users: {{ App\Models\User::where('role', 'admin')->count() }}
                },
                manager: {
                    name: 'Manager',
                    description: 'Management access with limited permissions',
                    permissions: ['Sales & POS', 'Inventory management', 'Reports access', 'Customer management'],
                    users: {{ App\Models\User::where('role', 'manager')->count() }}
                },
                cashier: {
                    name: 'Cashier',
                    description: 'Sales operations with basic permissions',
                    permissions: ['POS access', 'Sales creation', 'Customer management'],
                    users: {{ App\Models\User::where('role', 'cashier')->count() }}
                },
                staff: {
                    name: 'Staff',
                    description: 'Limited access for basic operations',
                    permissions: ['View products', 'View sales'],
                    users: {{ App\Models\User::where('role', 'staff')->count() }}
                },
                supervisor: {
                    name: 'Supervisor',
                    description: 'Supervisory access with monitoring permissions',
                    permissions: ['Monitor sales', 'View reports', 'Staff management'],
                    users: {{ App\Models\User::where('role', 'supervisor')->count() }}
                },
                auditor: {
                    name: 'Auditor',
                    description: 'Audit access with read-only permissions',
                    permissions: ['View all data', 'Generate reports', 'Audit logs'],
                    users: {{ App\Models\User::where('role', 'auditor')->count() }}
                }
            };
            
            const role = roleDetails[roleName];
            if (role) {
                alert(`Role Details: ${role.name}\n\nDescription: ${role.description}\n\nPermissions:\n${role.permissions.map(p => '• ' + p).join('\n')}\n\nUsers: ${role.users} assigned`);
            }
        },
        
        editRole(roleName) {
            // Edit role functionality
            alert(`Edit role dialog would open for: ${roleName}\n\n• Modify permissions\n• Change role description\n• Update user assignments`);
        },
        
        exportRoles() {
            // Export roles functionality
            alert('Export roles and permissions to CSV/PDF would be implemented here');
        },
        
        toggleMatrixView() {
            // Toggle between grid and list view
            this.matrixView = this.matrixView === 'grid' ? 'list' : 'grid';
            alert(`Switching to ${this.matrixView} view`);
        }
    }
}
</script>
@endsection











