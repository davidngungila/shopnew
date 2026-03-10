@extends('layouts.app')

@section('title', 'Security Settings')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Security Settings</h1>
            <p class="text-gray-600 mt-1">Manage your password and security preferences</p>
        </div>
        <a href="{{ route('profile.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Change Password</h2>
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <div class="relative">
                            <input type="password" name="current_password" required class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        @error('current_password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <div class="relative">
                            <input type="password" name="password" required class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-key absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <div class="mt-2">
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                                <span class="text-xs text-gray-500">Strong</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Use 8+ characters with mixed case and numbers</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-check absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors" style="background-color: #009245;">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Two-Factor Authentication</h2>
                <i class="fas fa-shield-alt text-gray-400"></i>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">SMS Authentication</p>
                            <p class="text-xs text-gray-500">Receive codes via SMS</p>
                        </div>
                    </div>
                    <button class="text-sm text-green-600 hover:text-green-700 font-medium">Enable</button>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Email Authentication</p>
                            <p class="text-xs text-gray-500">Receive codes via email</p>
                        </div>
                    </div>
                    <button class="text-sm text-green-600 hover:text-green-700 font-medium">Enable</button>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-qrcode text-gray-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Authenticator App</p>
                            <p class="text-xs text-gray-500">Use Google Authenticator</p>
                        </div>
                    </div>
                    <button class="text-sm text-green-600 hover:text-green-700 font-medium">Setup</button>
                </div>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Active Sessions</h2>
                <i class="fas fa-desktop text-gray-400"></i>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-laptop text-gray-400"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Windows PC - Chrome</p>
                            <p class="text-xs text-gray-500">Dar es Salaam • Current session</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Current</span>
                        <button class="text-sm text-red-600 hover:text-red-700">Revoke</button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-mobile-alt text-gray-400"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">iPhone - Safari</p>
                            <p class="text-xs text-gray-500">Dar es Salaam • 2 days ago</p>
                        </div>
                    </div>
                    <button class="text-sm text-red-600 hover:text-red-700">Revoke</button>
                </div>
                
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-tablet-alt text-gray-400"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">iPad - Safari</p>
                            <p class="text-xs text-gray-500">Nairobi • 1 week ago</p>
                        </div>
                    </div>
                    <button class="text-sm text-red-600 hover:text-red-700">Revoke</button>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start space-x-2">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Session Security</p>
                        <p class="text-xs text-gray-600">If you notice any unfamiliar sessions, revoke them immediately and change your password.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Alerts -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Login Alerts</h2>
                <i class="fas fa-bell text-gray-400"></i>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Email Notifications</p>
                        <p class="text-xs text-gray-500">Get notified of new login attempts</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">SMS Notifications</p>
                        <p class="text-xs text-gray-500">Get SMS alerts for suspicious activity</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Push Notifications</p>
                        <p class="text-xs text-gray-500">Browser push notifications</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Security Information</h2>
                <i class="fas fa-info-circle text-gray-400"></i>
            </div>
            <div class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at ? $user->updated_at->format('M d, Y h:i A') : 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Account Created</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($user->email_verified_at)
                        <span class="text-green-600">Verified on {{ $user->email_verified_at->format('M d, Y') }}</span>
                        @else
                        <span class="text-red-600">Not verified</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Password Last Changed</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->password_changed_at ? $user->password_changed_at->format('M d, Y') : 'Never' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Two-Factor Status</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <span class="text-yellow-600">Not Enabled</span>
                    </dd>
                </div>
            </div>
        </div>

        <!-- Recent Security Events -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Security Events</h2>
                <i class="fas fa-history text-gray-400"></i>
            </div>
            <div class="space-y-3">
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-sign-in-alt text-green-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Successful Login</p>
                        <p class="text-xs text-gray-500">Windows PC - Chrome • 2 hours ago</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-key text-yellow-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Password Changed</p>
                        <p class="text-xs text-gray-500">Web interface • 3 days ago</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Failed Login Attempt</p>
                        <p class="text-xs text-gray-500">Unknown device • 5 days ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-red-600">Danger Zone</h2>
            <i class="fas fa-exclamation-triangle text-red-400"></i>
        </div>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">Delete Account</p>
                    <p class="text-xs text-gray-600">Permanently delete your account and all data</p>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                    Delete Account
                </button>
            </div>
        </div>
        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex items-start space-x-2">
                <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-gray-900">Warning</p>
                    <p class="text-xs text-gray-600">Account deletion is permanent and cannot be undone. All your data, orders, and preferences will be lost.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

