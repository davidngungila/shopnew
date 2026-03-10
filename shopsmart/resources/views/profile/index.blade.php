@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600 mt-1">Manage your account settings and preferences</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="px-4 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">Edit Profile</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    @if($user && $user->name)
                    <div class="relative inline-block">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4" style="background-color: #009245;">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="absolute bottom-2 right-0 w-6 h-6 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name ?? 'User' }}</h2>
                    <p class="text-gray-500 mt-1">{{ $user->email ?? 'No email' }}</p>
                    @if($user->role ?? null)
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full" style="background-color: #e6f5ed; color: #009245;">
                        {{ ucfirst($user->role) }}
                    </span>
                    @endif
                    
                    <!-- Account Status -->
                    <div class="mt-4 space-y-2">
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Account Active</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-shield-alt text-green-600 text-sm"></i>
                            <span class="text-sm text-gray-600">Verified Member</span>
                        </div>
                    </div>
                    @else
                    <div class="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                        ?
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">No User Found</h2>
                    <p class="text-gray-500 mt-1">Please log in or create a user account</p>
                    @endif
                </div>
                
                @if($user)
                <div class="mt-6 space-y-3">
                    <div class="flex items-center space-x-3 text-sm">
                        <i class="fas fa-phone text-gray-400 w-5"></i>
                        <span class="text-gray-600">{{ $user->phone ?? 'Not set' }}</span>
                    </div>
                    <div class="flex items-start space-x-3 text-sm">
                        <i class="fas fa-map-marker-alt text-gray-400 w-5 mt-0.5"></i>
                        <span class="text-gray-600">{{ $user->address ?? 'Not set' }}</span>
                    </div>
                    <div class="flex items-center space-x-3 text-sm">
                        <i class="fas fa-calendar text-gray-400 w-5"></i>
                        <span class="text-gray-600">Joined {{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex items-center space-x-3 text-sm">
                        <i class="fas fa-clock text-gray-400 w-5"></i>
                        <span class="text-gray-600">Last active {{ $user->updated_at ? $user->updated_at->diffForHumans() : 'N/A' }}</span>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Account Statistics</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-lg font-bold text-green-600">127</div>
                            <div class="text-xs text-gray-500">Orders</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-lg font-bold text-blue-600">45</div>
                            <div class="text-xs text-gray-500">Reviews</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-lg font-bold text-purple-600">23</div>
                            <div class="text-xs text-gray-500">Wishlist</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-lg font-bold text-orange-600">89%</div>
                            <div class="text-xs text-gray-500">Profile Complete</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Profile Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                    <button class="text-sm text-green-600 hover:text-green-700 font-medium">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                </div>
                @if($user)
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->name ?? 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->email ?? 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->gender ?? 'Not specified') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nationality</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->nationality ?? 'Not set' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->address ?? 'Not set' }}</dd>
                    </div>
                    @if($user->bio ?? null)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Bio</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->bio }}</dd>
                    </div>
                    @endif
                </dl>
                @else
                <p class="text-gray-500">No user information available.</p>
                @endif
            </div>

            <!-- Shopping Preferences -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Shopping Preferences</h3>
                    <button class="text-sm text-green-600 hover:text-green-700 font-medium">
                        <i class="fas fa-cog mr-1"></i>Configure
                    </button>
                </div>
                @if($user)
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Preferred Language</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ strtoupper($user->language ?? 'en') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Currency</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->currency ?? 'TZS' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Preferred Categories</dt>
                        <dd class="mt-1 text-sm text-gray-900">Electronics, Fashion, Home</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Price Range</dt>
                        <dd class="mt-1 text-sm text-gray-900">TZS 10,000 - 500,000</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Delivery Preference</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->delivery_preference ?? 'Standard') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Methods</dt>
                        <dd class="mt-1 text-sm text-gray-900">Mobile Money, Card</dd>
                    </div>
                </dl>
                @else
                <p class="text-gray-500">No preferences available.</p>
                @endif
            </div>

            <!-- System Preferences -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">System Preferences</h3>
                    <button class="text-sm text-green-600 hover:text-green-700 font-medium">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                </div>
                @if($user)
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->timezone ?? 'Africa/Dar_es_Salaam' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date Format</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->date_format ?? 'd/m/Y' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Theme</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $user->theme ?? 'light' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Language</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ strtoupper($user->language ?? 'en') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Notifications</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ($user->notifications_email ?? true) ? 'Enabled' : 'Disabled' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">SMS Notifications</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ($user->notifications_sms ?? false) ? 'Enabled' : 'Disabled' }}</dd>
                    </div>
                </dl>
                @else
                <p class="text-gray-500">No preferences available.</p>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                    <a href="{{ route('profile.activity') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-green-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Order #12345 placed</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                        <span class="text-sm font-semibold text-green-600">TZS 45,000</span>
                    </div>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-star text-blue-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Reviewed Wireless Headphones</p>
                            <p class="text-xs text-gray-500">1 day ago</p>
                        </div>
                        <div class="flex text-xs">
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-heart text-purple-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Added 3 items to wishlist</p>
                            <p class="text-xs text-gray-500">3 days ago</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-edit text-green-600"></i>
                        <span class="text-sm font-medium text-gray-700">Edit Profile</span>
                    </a>
                    <a href="{{ route('profile.security') }}" class="flex items-center space-x-3 px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-lock text-green-600"></i>
                        <span class="text-sm font-medium text-gray-700">Security</span>
                    </a>
                    <a href="{{ route('profile.activity') }}" class="flex items-center space-x-3 px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-history text-green-600"></i>
                        <span class="text-sm font-medium text-gray-700">Activity</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-credit-card text-green-600"></i>
                        <span class="text-sm font-medium text-gray-700">Payment</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-bell text-green-600"></i>
                        <span class="text-sm font-medium text-gray-700">Notifications</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-question-circle text-green-600"></i>
                        <span class="text-sm font-medium text-gray-700">Help</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

