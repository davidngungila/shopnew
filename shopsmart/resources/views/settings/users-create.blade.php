@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="space-y-6" x-data="addUserForm()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Add New User</h1>
            <p class="text-gray-600 mt-1">Create a new user account for the system</p>
        </div>
        <div class="flex gap-2">
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

    <!-- Add User Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">User Information</h2>
            <span class="text-sm text-gray-500">Fill in the required information below</span>
        </div>

        <form action="{{ route('settings.users.store') }}" method="POST" @submit="validateAndSubmit($event)">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-md font-medium text-gray-900 mb-4">Personal Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="first_name" name="first_name" x-model="form.first_name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter first name">
                                @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="last_name" name="last_name" x-model="form.last_name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter last name">
                                @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" x-model="form.email" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter email address">
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Phone Number
                                </label>
                                <input type="tel" id="phone" name="phone" x-model="form.phone"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter phone number">
                                @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div>
                        <h3 class="text-md font-medium text-gray-900 mb-4">Account Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="username" name="username" x-model="form.username" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter username">
                                @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select id="role" name="role" x-model="form.role" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Select Role</option>
                                    <option value="admin">Administrator</option>
                                    <option value="manager">Manager</option>
                                    <option value="sales">Sales</option>
                                    <option value="cashier">Cashier</option>
                                </select>
                                @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Account Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status" name="status" x-model="form.status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Security Information -->
                    <div>
                        <h3 class="text-md font-medium text-gray-900 mb-4">Security Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" x-model="form.password" required
                                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter password">
                                    <button type="button" @click="togglePassword('password')" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye" :class="{'fa-eye-slash': passwordFields.password.visible}"></i>
                                    </button>
                                </div>
                                @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Minimum 8 characters</p>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" x-model="form.password_confirmation" required
                                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Confirm password">
                                    <button type="button" @click="togglePassword('password_confirmation')" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye" :class="{'fa-eye-slash': passwordFields.password_confirmation.visible}"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <h3 class="text-md font-medium text-gray-900 mb-4">Additional Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Address
                                </label>
                                <textarea id="address" name="address" x-model="form.address" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter address"></textarea>
                                @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                                    Bio/Notes
                                </label>
                                <textarea id="bio" name="bio" x-model="form.bio" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter bio or notes about the user"></textarea>
                                @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Role Information -->
                    <div>
                        <h3 class="text-md font-medium text-gray-900 mb-4">Role Permissions</h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium mb-2">Role Overview:</p>
                                    <ul class="space-y-1 text-xs">
                                        <li><strong>Administrator:</strong> Full system access and user management</li>
                                        <li><strong>Manager:</strong> Manage sales, inventory, and reports</li>
                                        <li><strong>Sales:</strong> Create sales and manage customers</li>
                                        <li><strong>Cashier:</strong> Process sales and view basic reports</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                <div class="text-sm text-gray-500">
                    <span class="text-red-500">*</span> Required fields
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="resetForm()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" :disabled="isSubmitting"
                        class="px-4 py-2 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" 
                        style="background-color: #009245;">
                        <i class="fas fa-save mr-2"></i>
                        <span x-show="!isSubmitting">Create User</span>
                        <span x-show="isSubmitting">Creating...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function addUserForm() {
    return {
        form: {
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            username: '',
            role: 'cashier',
            status: 'active',
            password: '',
            password_confirmation: '',
            address: '',
            bio: ''
        },
        isSubmitting: false,
        passwordFields: {
            password: { visible: false },
            password_confirmation: { visible: false }
        },

        togglePassword(field) {
            this.passwordFields[field].visible = !this.passwordFields[field].visible;
            const input = document.getElementById(field);
            input.type = this.passwordFields[field].visible ? 'text' : 'password';
        },

        validateAndSubmit(event) {
            // Basic client-side validation
            if (!this.form.first_name || !this.form.last_name || !this.form.email || !this.form.username || !this.form.password) {
                event.preventDefault();
                alert('Please fill in all required fields');
                return;
            }

            if (this.form.password !== this.form.password_confirmation) {
                event.preventDefault();
                alert('Password and confirmation must match');
                return;
            }

            if (this.form.password.length < 8) {
                event.preventDefault();
                alert('Password must be at least 8 characters long');
                return;
            }

            this.isSubmitting = true;
            // Form will submit normally
        },

        resetForm() {
            if (confirm('Are you sure you want to clear the form? All unsaved data will be lost.')) {
                this.form = {
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    username: '',
                    role: 'cashier',
                    status: 'active',
                    password: '',
                    password_confirmation: '',
                    address: '',
                    bio: ''
                };
                
                // Clear any validation errors
                const errorElements = document.querySelectorAll('.text-red-600');
                errorElements.forEach(el => el.remove());
            }
        }
    }
}
</script>
@endsection
