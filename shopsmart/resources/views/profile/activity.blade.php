@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Activity Log</h1>
            <p class="text-gray-600 mt-1">View your account activity history</p>
        </div>
        <a href="{{ route('profile.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Back</a>
    </div>

    <!-- Activity Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Type</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All Activities</option>
                    <option value="orders">Orders</option>
                    <option value="reviews">Reviews</option>
                    <option value="wishlist">Wishlist</option>
                    <option value="profile">Profile Updates</option>
                    <option value="login">Login Activity</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="7">Last 7 days</option>
                    <option value="30" selected>Last 30 days</option>
                    <option value="90">Last 90 days</option>
                    <option value="365">Last year</option>
                    <option value="all">All time</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" placeholder="Search activities..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Activities</h3>
        
        <!-- Today -->
        <div class="mb-8">
            <h4 class="text-sm font-semibold text-gray-500 mb-4">TODAY</h4>
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Order #12345 placed</p>
                            <span class="text-xs text-gray-500">2 hours ago</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">You placed an order for 3 items totaling TZS 45,000</p>
                        <div class="mt-2 flex items-center space-x-4">
                            <a href="#" class="text-sm text-green-600 hover:text-green-700 font-medium">View Order</a>
                            <span class="text-sm text-gray-400">•</span>
                            <span class="text-sm text-gray-500">Order ID: #12345</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-sign-in-alt text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Login from new device</p>
                            <span class="text-xs text-gray-500">5 hours ago</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Logged in from Chrome on Windows (Dar es Salaam)</p>
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">New Device</span>
                            <span class="text-xs text-gray-500">IP: 192.168.1.1</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-purple-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Added items to wishlist</p>
                            <span class="text-xs text-gray-500">8 hours ago</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Added 3 items to your wishlist</p>
                        <div class="mt-2">
                            <div class="flex flex-wrap gap-2">
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">Wireless Headphones</span>
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">Smart Watch</span>
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">Yoga Mat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Yesterday -->
        <div class="mb-8">
            <h4 class="text-sm font-semibold text-gray-500 mb-4">YESTERDAY</h4>
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-yellow-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Reviewed Wireless Headphones</p>
                            <span class="text-xs text-gray-500">1 day ago</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">You left a 5-star review</p>
                        <div class="mt-2 flex items-center space-x-2">
                            <div class="flex text-xs">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                            <a href="#" class="text-sm text-green-600 hover:text-green-700 font-medium">View Review</a>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-truck text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Order #12344 delivered</p>
                            <span class="text-xs text-gray-500">1 day ago</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Your order was successfully delivered</p>
                        <div class="mt-2 flex items-center space-x-4">
                            <a href="#" class="text-sm text-green-600 hover:text-green-700 font-medium">View Order</a>
                            <span class="text-sm text-gray-400">•</span>
                            <span class="text-sm text-green-600">Delivered</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-credit-card text-orange-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Payment method added</p>
                            <span class="text-xs text-gray-500">1 day ago</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Added Visa card ending in 4242</p>
                        <div class="mt-2">
                            <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded">New Payment Method</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Week -->
        <div class="mb-8">
            <h4 class="text-sm font-semibold text-gray-500 mb-4">THIS WEEK</h4>
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Order #12343 cancelled</p>
                            <span class="text-xs text-gray-500">3 days ago</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">You cancelled order #12343</p>
                        <div class="mt-2 flex items-center space-x-4">
                            <a href="#" class="text-sm text-green-600 hover:text-green-700 font-medium">View Order</a>
                            <span class="text-sm text-gray-400">•</span>
                            <span class="text-sm text-red-600">Cancelled</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-edit text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Profile updated</p>
                            <span class="text-xs text-gray-500">4 days ago</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">You updated your profile information</p>
                        <div class="mt-2">
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Profile Update</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-gift text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Reward points earned</p>
                            <span class="text-xs text-gray-500">5 days ago</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Earned 250 points from order #12342</p>
                        <div class="mt-2 flex items-center space-x-4">
                            <span class="text-sm font-semibold text-green-600">+250 points</span>
                            <a href="#" class="text-sm text-green-600 hover:text-green-700 font-medium">View Rewards</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load More -->
        <div class="text-center mt-8">
            <button class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                Load More Activities
            </button>
        </div>
    </div>

    <!-- Activity Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900">127</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-bag text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Reviews Written</p>
                    <p class="text-2xl font-bold text-gray-900">45</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-star text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Wishlist Items</p>
                    <p class="text-2xl font-bold text-gray-900">23</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-heart text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Reward Points</p>
                    <p class="text-2xl font-bold text-gray-900">2,450</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-coins text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection











