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
            <button @click="showAddUserModal()" class="px-4 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-user-plus mr-2"></i>Add User
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

    <!-- User Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $users->total() }}</p>
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
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
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
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">@{{ $user->username ?? strtolower(str_replace(' ', '', $user->name)) }}</div>
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
                                <button @click="editUser({{ $user->toJson() }})" class="text-blue-600 hover:text-blue-900" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="viewUser({{ $user->toJson() }})" class="text-green-600 hover:text-green-900" title="View User">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button @click="resetPassword({{ $user->toJson() }})" class="text-orange-600 hover:text-orange-900" title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button @click="deleteUser({{ $user->toJson() }})" class="text-red-600 hover:text-red-900" title="Delete User">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
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
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" x-model="userForm.name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
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
</div>

<script>
function userManagement() {
    return {
        search: '',
        roleFilter: '',
        statusFilter: '',
        showUserModal: false,
        editingUser: null,
        userForm: {
            name: '',
            email: '',
            phone: '',
            role: 'cashier',
            status: 'active'
        },
        
        filterUsers() {
            // Filter logic would be implemented here
            console.log('Filtering users...', {
                search: this.search,
                role: this.roleFilter,
                status: this.statusFilter
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
                name: '',
                email: '',
                phone: '',
                role: 'cashier',
                status: 'active'
            };
            this.showUserModal = true;
        },
        
        editUser(user) {
            this.editingUser = user;
            this.userForm = { ...user };
            this.showUserModal = true;
        },
        
        viewUser(user) {
            alert(`View user: ${user.name}`);
        },
        
        saveUser() {
            // Save user logic would be implemented here
            console.log('Saving user...', this.userForm);
            this.showUserModal = false;
        },
        
        resetPassword(user) {
            if (confirm(`Are you sure you want to reset password for ${user.name}?`)) {
                alert(`Password reset link would be sent to: ${user.email}`);
            }
        },
        
        deleteUser(user) {
            if (confirm(`Are you sure you want to delete ${user.name}? This action cannot be undone.`)) {
                alert(`User ${user.name} would be deleted`);
            }
        },
        
        exportUsers() {
            window.location.href = '{{ route('settings.users.export') }}';
        },
        
        sortBy(field) {
            console.log('Sorting by:', field);
        }
    }
}
</script>
@endsection











