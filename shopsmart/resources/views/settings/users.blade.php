@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="space-y-6" x-data="userManagement()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">User Management</h1>
            <p class="text-gray-600 mt-1">Manage system users, roles, and permissions</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('settings.users.create') }}" class="px-4 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-user-plus mr-2"></i>Add User
            </a>
            <button @click="importUsers()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-file-import mr-2"></i>Import
            </button>
            <button @click="exportUsers()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-download mr-2"></i>Export
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

    <!-- User Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $users->total() ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-users text-blue-500"></i> 
                        Registered accounts
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Users</p>
                    <p class="text-2xl font-bold text-green-600">{{ App\Models\User::where('status', 'active')->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-user-check text-green-500"></i> 
                        Currently active
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Admin Users</p>
                    <p class="text-2xl font-bold text-purple-600">{{ App\Models\User::where('role', 'admin')->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-user-shield text-purple-500"></i> 
                        System administrators
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">New This Month</p>
                    <p class="text-2xl font-bold text-orange-600">{{ App\Models\User::whereMonth('created_at', now()->month)->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-user-plus text-orange-500"></i> 
                        New registrations
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
            <span class="text-sm text-gray-500">Common user management tasks</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <button @click="bulkActivate()" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-user-check text-green-600"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-sm font-medium text-gray-900">Bulk Activate</h3>
                    <p class="text-xs text-gray-500">Activate selected users</p>
                </div>
            </button>

            <button @click="bulkDeactivate()" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-user-times text-orange-600"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-sm font-medium text-gray-900">Bulk Deactivate</h3>
                    <p class="text-xs text-gray-500">Deactivate selected users</p>
                </div>
            </button>

            <button @click="sendBulkEmail()" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-envelope text-blue-600"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-sm font-medium text-gray-900">Send Email</h3>
                    <p class="text-xs text-gray-500">Email all users</p>
                </div>
            </button>

            <button @click="viewActivityLog()" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-history text-purple-600"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-sm font-medium text-gray-900">Activity Log</h3>
                    <p class="text-xs text-gray-500">View user activities</p>
                </div>
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" x-model="search" @input="filterUsers()" 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="Search users by name, email...">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            <select x-model="roleFilter" @change="filterUsers()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="manager">Manager</option>
                <option value="cashier">Cashier</option>
                <option value="sales">Sales</option>
            </select>
            <select x-model="statusFilter" @change="filterUsers()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
            <select x-model="dateFilter" @change="filterUsers()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <input type="checkbox" @change="toggleSelectAll()" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                <span class="text-sm text-gray-600">Select All</span>
            </div>
            <div x-show="selectedUsers.length > 0" class="flex items-center space-x-2">
                <span class="text-sm text-gray-600" x-text="`${selectedUsers.length} selected`"></span>
                <button @click="bulkDelete()" class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-sm hover:bg-red-200 transition-colors">
                    <i class="fas fa-trash mr-1"></i>Delete Selected
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" @change="toggleSelectAll()" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span>User</span>
                                <button @click="sortBy('name')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users ?? [] as $user)
                    <tr class="hover:bg-gray-50 transition-colors" x-show="matchesFilter({{ $user->toJson() }})">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" :value="{{ $user->id }}" @change="toggleUserSelection({{ $user->id }})" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">@{{ $user->username ?? strtolower(str_replace(' ', '', $user->name)) }}</div>
                                    @if($user->id === auth()->id())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>You
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                    {{ $user->email }}
                                </div>
                            </div>
                            @if($user->phone)
                            <div class="text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-400 mr-2"></i>
                                    {{ $user->phone }}
                                </div>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $user->role === 'manager' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $user->role === 'cashier' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $user->role === 'sales' ? 'bg-orange-100 text-orange-800' : '' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $user->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $user->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <button @click="viewUser($user->id)" class="text-green-600 hover:text-green-900" title="View User" :data-user="@json($user)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button @click="editUser($user->id)" class="text-blue-600 hover:text-blue-900" title="Edit User" :data-user="@json($user)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="resetPassword($user->id)" class="text-orange-600 hover:text-orange-900" title="Reset Password" :data-user="@json($user)">
                                    <i class="fas fa-key"></i>
                                </button>
                                <button @click="toggleUserStatus($user->id)" class="text-purple-600 hover:text-purple-900" title="Toggle Status" :data-user="@json($user)">
                                    <i class="fas fa-toggle-on"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button @click="deleteUser($user->id)" class="text-red-600 hover:text-red-900" title="Delete User" :data-user="@json($user)">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-center">
                                <i class="fas fa-users text-gray-400 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg">No users found</p>
                                <p class="text-gray-400 text-sm mt-2">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(isset($users) && $users->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
                <a href="{{ $users->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $users->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $users->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $users->total() }}</span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            {{ $users->currentPage() }}
                        </span>
                        <a href="{{ $users->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Add/Edit User Modal -->
    <div x-show="showUserModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showUserModal = false"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                <span x-text="editingUser ? 'Edit User' : 'Add New User'"></span>
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                                        <input type="text" x-model="userForm.first_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                        <input type="text" x-model="userForm.last_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" x-model="userForm.email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="tel" x-model="userForm.phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Username</label>
                                    <input type="text" x-model="userForm.username" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Role</label>
                                    <select x-model="userForm.role" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                        <option value="admin">Administrator</option>
                                        <option value="manager">Manager</option>
                                        <option value="cashier">Cashier</option>
                                        <option value="sales">Sales</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <select x-model="userForm.status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                                <div x-show="!editingUser">
                                    <label class="block text-sm font-medium text-gray-700">Password</label>
                                    <input type="password" x-model="userForm.password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                </div>
                                <div x-show="!editingUser">
                                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input type="password" x-model="userForm.password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="saveUser()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm" style="background-color: #009245;">
                        <span x-text="editingUser ? 'Update User' : 'Create User'"></span>
                    </button>
                    <button type="button" @click="showUserModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div x-show="showViewModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="view-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showViewModal = false"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="view-modal-title">User Details</h3>
                            <div class="mt-4">
                                <div class="flex items-center mb-6">
                                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center mr-4">
                                        <span class="text-white font-bold text-xl" x-text="viewUser.name ? viewUser.name.charAt(0).toUpperCase() : ''"></span>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900" x-text="viewUser.name"></h4>
                                        <p class="text-sm text-gray-500" x-text="'@' + (viewUser.username || '')"></p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-circle mr-1" x-text="viewUser.status === 'active' ? 'fas fa-circle' : 'fas fa-circle'"></i>
                                            <span x-text="viewUser.status ? viewUser.status.charAt(0).toUpperCase() + viewUser.status.slice(1) : 'Active'"></span>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-900 mb-3">Contact Information</h5>
                                        <dl class="space-y-2">
                                            <div class="flex justify-between">
                                                <dt class="text-sm text-gray-500">Email</dt>
                                                <dd class="text-sm text-gray-900" x-text="viewUser.email"></dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm text-gray-500">Phone</dt>
                                                <dd class="text-sm text-gray-900" x-text="viewUser.phone || 'Not provided'"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                    
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-900 mb-3">Account Details</h5>
                                        <dl class="space-y-2">
                                            <div class="flex justify-between">
                                                <dt class="text-sm text-gray-500">Role</dt>
                                                <dd class="text-sm text-gray-900" x-text="viewUser.role ? viewUser.role.charAt(0).toUpperCase() + viewUser.role.slice(1) : ''"></dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm text-gray-500">Member Since</dt>
                                                <dd class="text-sm text-gray-900" x-text="viewUser.created_at ? new Date(viewUser.created_at).toLocaleDateString() : ''"></dd>
                                            </div>
                                            <div class="flex justify-between">
                                                <dt class="text-sm text-gray-500">Last Login</dt>
                                                <dd class="text-sm text-gray-900" x-text="viewUser.last_login_at ? new Date(viewUser.last_login_at).toLocaleString() : 'Never'"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showViewModal = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function userManagement() {
    return {
        search: '',
        roleFilter: '',
        statusFilter: '',
        dateFilter: '',
        showUserModal: false,
        showViewModal: false,
        editingUser: null,
        viewUser: {},
        selectedUsers: [],
        users: @json($users->items()),
        userForm: {
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            username: '',
            role: 'cashier',
            status: 'active',
            password: '',
            password_confirmation: ''
        },
        
        init() {
            // Initialize component
        },
        
        filterUsers() {
            // Filter logic would be implemented here
            console.log('Filtering users...', {
                search: this.search,
                role: this.roleFilter,
                status: this.statusFilter,
                date: this.dateFilter
            });
        },
        
        matchesFilter(user) {
            const matchesSearch = !this.search || 
                user.name.toLowerCase().includes(this.search.toLowerCase()) ||
                user.email.toLowerCase().includes(this.search.toLowerCase());
            
            const matchesRole = !this.roleFilter || user.role === this.roleFilter;
            const matchesStatus = !this.statusFilter || user.status === this.statusFilter;
            
            return matchesSearch && matchesRole && matchesStatus;
        },
        
        showAddUserModal() {
            this.editingUser = null;
            this.userForm = {
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                username: '',
                role: 'cashier',
                status: 'active',
                password: '',
                password_confirmation: ''
            };
            this.showUserModal = true;
        },
        
        editUser(user) {
            this.editingUser = user;
            this.userForm = { 
                ...user,
                password: '',
                password_confirmation: ''
            };
            this.showUserModal = true;
        },
        
        viewUser(user) {
            this.viewUser = user;
            this.showViewModal = true;
        },
        
        saveUser() {
            // Validate form
            if (!this.userForm.first_name || !this.userForm.last_name || !this.userForm.email) {
                alert('Please fill in all required fields');
                return;
            }
            
            if (!this.editingUser && (!this.userForm.password || this.userForm.password !== this.userForm.password_confirmation)) {
                alert('Password and confirmation must match');
                return;
            }
            
            // Combine first and last name
            this.userForm.name = this.userForm.first_name + ' ' + this.userForm.last_name;
            
            // Submit form to server
            const form = document.createElement('form');
            form.method = this.editingUser ? 'POST' : 'POST';
            form.action = this.editingUser ? `/settings/users/${this.editingUser.id}` : '/settings/users';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add method override for PUT requests
            if (this.editingUser) {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            }
        },
        
        viewUser(userId) {
            const user = this.users.find(u => u.id === userId);
            if (user) {
                this.viewUser = user;
                this.showViewModal = true;
            }
        },
        
        editUser(userId) {
            const user = this.users.find(u => u.id === userId);
            if (user) {
                this.editingUser = user;
                this.userForm = { 
                    ...user,
                    password: '',
                    password_confirmation: ''
                };
                this.showUserModal = true;
            }
        },
        
        resetPassword(userId) {
            const user = this.users.find(u => u.id === userId);
            if (user) {
                if (confirm(`Are you sure you want to reset password for ${user.name}? A reset link would be sent to their email.`)) {
                    alert(`Password reset link would be sent to: ${user.email}\n\nThis functionality requires backend route implementation.`);
                }
            }
        },
        
        toggleUserStatus(userId) {
            const user = this.users.find(u => u.id === userId);
            if (user) {
                const newStatus = user.status === 'active' ? 'inactive' : 'active';
                if (confirm(`Are you sure you want to ${newStatus} ${user.name}?`)) {
                    alert(`User status would be changed to: ${newStatus}\n\nThis functionality requires backend route implementation.`);
                }
            }
        },
        
        deleteUser(userId) {
            const user = this.users.find(u => u.id === userId);
            if (user) {
                if (confirm(`Are you sure you want to delete ${user.name}? This action cannot be undone.`)) {
                    // Submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/settings/users/${user.id}`;
                    
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
            }
        },
        
        toggleSelectAll() {
            // Toggle all user selection
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = !checkbox.checked;
                const userId = parseInt(checkbox.value);
                if (checkbox.checked) {
                    if (!this.selectedUsers.includes(userId)) {
                        this.selectedUsers.push(userId);
                    }
                } else {
                    this.selectedUsers = this.selectedUsers.filter(id => id !== userId);
                }
            });
        },
        
        toggleUserSelection(userId) {
            if (this.selectedUsers.includes(userId)) {
                this.selectedUsers = this.selectedUsers.filter(id => id !== userId);
            } else {
                this.selectedUsers.push(userId);
            }
        },
        
        bulkDelete() {
            if (confirm(`Are you sure you want to delete ${this.selectedUsers.length} selected users? This action cannot be undone.`)) {
                alert(`${this.selectedUsers.length} users would be deleted.\n\nThis functionality requires backend route implementation.`);
                this.selectedUsers = [];
            }
        },
        
        bulkActivate() {
            if (confirm('Are you sure you want to activate all selected users?')) {
                alert('Bulk activation would be processed here');
            }
        },
        
        bulkDeactivate() {
            if (confirm('Are you sure you want to deactivate all selected users?')) {
                alert('Bulk deactivation would be processed here');
            }
        },
        
        sendBulkEmail() {
            const subject = prompt('Enter email subject:');
            const message = prompt('Enter email message:');
            
            if (subject && message) {
                alert(`Bulk email would be sent:\n\nSubject: ${subject}\nMessage: ${message}\n\nTo all active users`);
            }
        },
        
        viewActivityLog() {
            window.location.href = '{{ route("settings.activity-log") }}';
        },
        
        importUsers() {
            alert('User import functionality would:\n\n• Accept CSV/Excel files\n• Validate data format\n• Create user accounts\n• Send welcome emails\n• Report import results');
        },
        
        exportUsers() {
            alert('User export functionality would:\n\n• Export all users to CSV\n• Include user details and roles\n• Download file automatically\n\nThis functionality requires backend route implementation.');
        },
        
        sortBy(field) {
            console.log('Sorting by:', field);
            // In a real application, this would update the sorting and refresh the table
        }
    }
}
</script>
@endsection











