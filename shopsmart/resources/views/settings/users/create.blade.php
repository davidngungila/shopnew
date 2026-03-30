@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="space-y-6" x-data="createUserForm()">
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

    <!-- User Creation Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('settings.users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Personal Information -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               x-model="form.first_name"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Enter first name">
                        @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               x-model="form.last_name"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Enter last name">
                        @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               x-model="form.phone"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="+255 123 456 789">
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Address
                        </label>
                        <textarea id="address" 
                                  name="address" 
                                  x-model="form.address"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Enter address"></textarea>
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               x-model="form.username"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Enter username">
                        @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               x-model="form.email"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="user@example.com">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               x-model="form.password"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Enter password">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2">
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                <span>Password Strength</span>
                                <span x-text="getPasswordStrength()"></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-300" 
                                     :class="getPasswordStrengthColor()" 
                                     :style="`width: ${getPasswordStrengthPercentage()}%`"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               x-model="form.password_confirmation"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Confirm password">
                        @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Role & Status -->
            <div class="pb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Role & Status</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            User Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" 
                                name="role" 
                                x-model="form.role"
                                required
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
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Account Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                x-model="form.status"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('settings.users') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        :disabled="!isFormValid()"
                        class="px-6 py-2 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
                        style="background-color: #009245;">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function createUserForm() {
    return {
        form: {
            first_name: '',
            last_name: '',
            phone: '',
            address: '',
            username: '',
            email: '',
            password: '',
            password_confirmation: '',
            role: 'cashier',
            status: 'active'
        },
        
        init() {
            // Initialize form
        },
        
        isFormValid() {
            return this.form.first_name && 
                   this.form.last_name && 
                   this.form.email && 
                   this.form.username && 
                   this.form.password && 
                   this.form.password_confirmation &&
                   this.form.password === this.form.password_confirmation &&
                   this.form.role &&
                   this.form.status;
        },
        
        getPasswordStrength() {
            const password = this.form.password;
            if (!password) return 'Empty';
            
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Character variety checks
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            if (strength <= 2) return 'Weak';
            if (strength <= 4) return 'Medium';
            return 'Strong';
        },
        
        getPasswordStrengthColor() {
            const strength = this.getPasswordStrength();
            if (strength === 'Weak') return 'bg-red-500';
            if (strength === 'Medium') return 'bg-yellow-500';
            return 'bg-green-500';
        },
        
        getPasswordStrengthPercentage() {
            const strength = this.getPasswordStrength();
            if (strength === 'Weak') return 33;
            if (strength === 'Medium') return 66;
            return 100;
        }
    };
}
</script>
@endsection
