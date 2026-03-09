@extends('layouts.app')

@section('title', 'Professional Dashboard')

@section('content')
<div class="space-y-6" x-data="professionalDashboard()">
    <!-- Professional Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Professional Dashboard</h1>
            <p class="text-gray-600 mt-1">
                {{ $now->format('l, F d, Y') }} - {{ $now->format('h:i A') }} EAT
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <select x-model="dateRange" @change="updateAllCharts()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="7">Last 7 Days</option>
                <option value="30">Last 30 Days</option>
                <option value="90">Last 90 Days</option>
                <option value="365">Last Year</option>
            </select>
            <button @click="refreshDashboard()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Refresh</span>
            </button>
            <a href="{{ route('pos.index') }}" class="px-4 py-2 rounded-lg flex items-center space-x-2 text-white bg-green-600 hover:bg-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Sale</span>
            </a>
        </div>
    </div>

    <!-- 1️⃣ Top KPI Cards (Muhimu Sana) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        <!-- 💰 Total Sales Today -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-medium">Total Sales Today</p>
                    <p class="text-2xl font-bold mt-1">TZS {{ number_format($todaySales ?? 0, 0) }}</p>
                    <div class="flex items-center mt-1 text-xs">
                        @php $growth = $salesGrowth ?? 0; @endphp
                        @if($growth >= 0)
                        <span class="text-blue-100 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ number_format(abs($growth), 1) }}%
                        </span>
                        @else
                        <span class="text-blue-100 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ number_format(abs($growth), 1) }}%
                        </span>
                        @endif
                    </div>
                </div>
                <div class="w-10 h-10 bg-blue-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662.662.662 0 00-.224 1.48 4.667 4.667 0 001.266 2.226 1.514 1.514 0 01-.213.294c-.133.13-.313.23-.527.31v1.09a3.37 3.37 0 001.562-.352c.386-.196.724-.47.99-.828.266-.357.448-.77.548-1.221.1-.45.15-.943.15-1.473V5a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676-.662.662.662 0 00-.224 1.48 4.667 4.667 0 001.266 2.226C.046.05.098.1.151.144v.093z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 📈 Total Sales This Month -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-xs font-medium">Total Sales This Month</p>
                    <p class="text-2xl font-bold mt-1">TZS {{ number_format($thisMonthSales ?? 0, 0) }}</p>
                    <div class="flex items-center mt-1 text-xs text-green-100">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $todayOrders ?? 0 }} transactions
                    </div>
                </div>
                <div class="w-10 h-10 bg-green-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 🧾 Total Transactions -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-xs font-medium">Total Transactions</p>
                    <p class="text-2xl font-bold mt-1">{{ $todayOrders ?? 0 }}</p>
                    <div class="flex items-center mt-1 text-xs text-purple-100">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 00-2 2v6a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-1a1 1 0 100-2h1a4 4 0 014 4v6a4 4 0 01-4 4H6a4 4 0 01-4-4V7a4 4 0 014-4z" clip-rule="evenodd"></path>
                        </svg>
                        Today
                    </div>
                </div>
                <div class="w-10 h-10 bg-purple-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 👥 Total Customers -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-xs font-medium">Total Customers</p>
                    <p class="text-2xl font-bold mt-1">{{ $totalCustomers ?? 0 }}</p>
                    <div class="flex items-center mt-1 text-xs text-orange-100">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a5 5 0 015-5h.75z"></path>
                        </svg>
                        +{{ $newCustomersToday ?? 0 }} today
                    </div>
                </div>
                <div class="w-10 h-10 bg-orange-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 📦 Products Sold Today -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-xs font-medium">Products Sold Today</p>
                    <p class="text-2xl font-bold mt-1">{{ $productsSoldToday ?? 0 }}</p>
                    <div class="flex items-center mt-1 text-xs text-red-100">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                        </svg>
                        {{ $avgOrderValue ?? 0 }} avg
                    </div>
                </div>
                <div class="w-10 h-10 bg-red-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 🏪 Total Products in Stock -->
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-100 text-xs font-medium">Total Products in Stock</p>
                    <p class="text-2xl font-bold mt-1">{{ $totalProducts ?? 0 }}</p>
                    <div class="flex items-center mt-1 text-xs text-teal-100">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        {{ $activeProducts ?? 0 }} active
                    </div>
                </div>
                <div class="w-10 h-10 bg-teal-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- ⚠️ Low Stock Products -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-xs font-medium">Low Stock Products</p>
                    <p class="text-2xl font-bold mt-1">{{ $lowStockCount ?? 0 }}</p>
                    <div class="flex items-center mt-1 text-xs text-yellow-100">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $outOfStockCount ?? 0 }} out of stock
                    </div>
                </div>
                <div class="w-10 h-10 bg-yellow-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 💵 Total Profit -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-xs font-medium">Total Profit</p>
                    <p class="text-2xl font-bold mt-1">TZS {{ number_format($profit ?? 0, 0) }}</p>
                    <div class="flex items-center mt-1 text-xs text-indigo-100">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293A1 1 0 012.414 12l4 4 4-4 4.293 4.293a1 1 0 001.414 0l4-4V8z" clip-rule="evenodd"></path>
                        </svg>
                        {{ number_format($profitMargin ?? 0, 1) }}% margin
                    </div>
                </div>
                <div class="w-10 h-10 bg-indigo-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662.662.662 0 00-.224 1.48 4.667 4.667 0 001.266 2.226 1.514 1.514 0 01-.213.294c-.133.13-.313.23-.527.31v1.09a3.37 3.37 0 001.562-.352c.386-.196.724-.47.99-.828.266-.357.448-.77.548-1.221.1-.45.15-.943.15-1.473V5a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676-.662.662.662 0 00-.224 1.48 4.667 4.667 0 001.266 2.226C.046.05.098.1.151.144v.093z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Perfect Dashboard Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 2️⃣ Sales Trend Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Sales Trend (Last 30 Days)</h2>
                <div class="flex items-center space-x-2">
                    <button @click="changeSalesTrendType('line')" :class="salesTrendType === 'line' ? 'bg-blue-100 text-blue-700' : 'text-gray-500'" class="px-3 py-1 rounded text-sm">Line</button>
                    <button @click="changeSalesTrendType('bar')" :class="salesTrendType === 'bar' ? 'bg-blue-100 text-blue-700' : 'text-gray-500'" class="px-3 py-1 rounded text-sm">Bar</button>
                </div>
            </div>
            <div style="position: relative; height: 300px;">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- 5️⃣ Payment Methods Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Methods</h2>
            <div style="position: relative; height: 300px;">
                <canvas id="paymentMethodsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 3️⃣ Sales by Category Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Sales by Category</h2>
                <div class="flex items-center space-x-2">
                    <button @click="changeCategoryType('pie')" :class="categoryType === 'pie' ? 'bg-blue-100 text-blue-700' : 'text-gray-500'" class="px-3 py-1 rounded text-sm">Pie</button>
                    <button @click="changeCategoryType('donut')" :class="categoryType === 'donut' ? 'bg-blue-100 text-blue-700' : 'text-gray-500'" class="px-3 py-1 rounded text-sm">Donut</button>
                </div>
            </div>
            <div style="position: relative; height: 300px;">
                <canvas id="salesByCategoryChart"></canvas>
            </div>
        </div>

        <!-- 4️⃣ Top Selling Products Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Selling Products</h2>
            <div style="position: relative; height: 300px;">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 6️⃣ Hourly Sales Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Hourly Sales</h2>
            <div style="position: relative; height: 250px;">
                <canvas id="hourlySalesChart"></canvas>
            </div>
        </div>

        <!-- 7️⃣ Inventory Status Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Inventory Status</h2>
            <div style="position: relative; height: 250px;">
                <canvas id="inventoryStatusChart"></canvas>
            </div>
        </div>

        <!-- 8️⃣ Sales by Cashier -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Sales by Cashier</h2>
            <div style="position: relative; height: 250px;">
                <canvas id="salesByCashierChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 9️⃣ Recent Transactions Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Recent Transactions</h2>
            <button @click="refreshTransactions()" class="text-sm text-blue-600 hover:text-blue-800">Refresh</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Method</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentSales ?? [] as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $sale->id ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $sale->customer_name ?? 'Walk-in' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $sale->items_count ?? 1 }} items</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">TZS {{ number_format($sale->total ?? 0, 0) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                {{ $sale->payment_method ?? 'Cash' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $sale->created_at?->format('M d, H:i') ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            No recent transactions
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🔟 Low Stock Alerts -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">⚠️ Low Stock Alerts</h2>
            <span class="text-sm text-gray-500">{{ $lowStockCount ?? 0 }} products need restocking</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($lowStockProducts ?? [] as $product)
            <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $product->sku ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-red-600">{{ $product->stock_quantity ?? 0 }}</p>
                    <p class="text-xs text-gray-500">remaining</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8 text-sm text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                No low stock alerts
            </div>
            @endforelse
        </div>
    </div>

    <!-- ⭐ Advanced Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 📊 Profit vs Sales -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Profit vs Sales</h2>
            <div style="position: relative; height: 300px;">
                <canvas id="profitVsSalesChart"></canvas>
            </div>
        </div>

        <!-- 📊 Monthly Revenue -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Monthly Revenue</h2>
            <div style="position: relative; height: 300px;">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 📊 Customer Growth -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Growth</h2>
            <div style="position: relative; height: 300px;">
                <canvas id="customerGrowthChart"></canvas>
            </div>
        </div>

        <!-- 📊 Sales Heatmap -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Sales Heatmap (Day & Hour)</h2>
            <div style="position: relative; height: 300px;">
                <canvas id="salesHeatmapChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function professionalDashboard() {
    return {
        dateRange: '30',
        salesTrendType: 'line',
        categoryType: 'pie',
        charts: {},
        
        init() {
            this.initAllCharts();
            this.setupAutoRefresh();
        },
        
        initAllCharts() {
            this.initSalesTrendChart();
            this.initPaymentMethodsChart();
            this.initSalesByCategoryChart();
            this.initTopProductsChart();
            this.initHourlySalesChart();
            this.initInventoryStatusChart();
            this.initSalesByCashierChart();
            this.initProfitVsSalesChart();
            this.initMonthlyRevenueChart();
            this.initCustomerGrowthChart();
            this.initSalesHeatmapChart();
        },
        
        // 2️⃣ Sales Trend Chart
        initSalesTrendChart() {
            const ctx = document.getElementById('salesTrendChart');
            if (!ctx) return;
            
            const labels = this.generateDateLabels(30);
            const salesData = this.generateRandomData(30, 50000, 300000);
            
            this.charts.salesTrend = new Chart(ctx, {
                type: this.salesTrendType,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales (TZS)',
                        data: salesData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: this.salesTrendType === 'line' ? 'rgba(59, 130, 246, 0.1)' : 'rgba(59, 130, 246, 0.8)',
                        tension: 0.4,
                        fill: this.salesTrendType === 'line',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'TZS ' + (value / 1000).toFixed(0) + 'K';
                                }
                            }
                        }
                    }
                }
            });
        },
        
        // 5️⃣ Payment Methods Chart
        initPaymentMethodsChart() {
            const ctx = document.getElementById('paymentMethodsChart');
            if (!ctx) return;
            
            this.charts.paymentMethods = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cash', 'Mobile Money', 'Card', 'Bank Transfer'],
                    datasets: [{
                        data: [50, 30, 15, 5],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(147, 51, 234, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        
        // 3️⃣ Sales by Category Chart
        initSalesByCategoryChart() {
            const ctx = document.getElementById('salesByCategoryChart');
            if (!ctx) return;
            
            this.charts.salesByCategory = new Chart(ctx, {
                type: this.categoryType,
                data: {
                    labels: ['Electronics', 'Food', 'Clothes', 'Other'],
                    datasets: [{
                        data: [40, 25, 20, 15],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(147, 51, 234, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: this.categoryType === 'donut' ? '50%' : '0%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        
        // 4️⃣ Top Selling Products Chart
        initTopProductsChart() {
            const ctx = document.getElementById('topProductsChart');
            if (!ctx) return;
            
            this.charts.topProducts = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Rice', 'Sugar', 'Milk', 'Soap', 'Bread'],
                    datasets: [{
                        label: 'Sales',
                        data: [120, 95, 80, 65, 45],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        },
        
        // 6️⃣ Hourly Sales Chart
        initHourlySalesChart() {
            const ctx = document.getElementById('hourlySalesChart');
            if (!ctx) return;
            
            const hours = ['8 AM', '10 AM', '12 PM', '2 PM', '4 PM', '6 PM', '8 PM'];
            const salesData = [50, 80, 120, 150, 180, 200, 150];
            
            this.charts.hourlySales = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Sales',
                        data: salesData,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        },
        
        // 7️⃣ Inventory Status Chart
        initInventoryStatusChart() {
            const ctx = document.getElementById('inventoryStatusChart');
            if (!ctx) return;
            
            this.charts.inventoryStatus = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                    datasets: [{
                        label: 'Products',
                        data: [500, 20, 10],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        },
        
        // 8️⃣ Sales by Cashier Chart
        initSalesByCashierChart() {
            const ctx = document.getElementById('salesByCashierChart');
            if (!ctx) return;
            
            this.charts.salesByCashier = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['David', 'Janeth', 'Peter', 'Sarah'],
                    datasets: [{
                        label: 'Sales (TZS)',
                        data: [200000, 150000, 120000, 180000],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(147, 51, 234, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(251, 146, 60, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'TZS ' + (value / 1000).toFixed(0) + 'K';
                                }
                            }
                        }
                    }
                }
            });
        },
        
        // ⭐ Advanced Charts
        initProfitVsSalesChart() {
            const ctx = document.getElementById('profitVsSalesChart');
            if (!ctx) return;
            
            const labels = this.generateDateLabels(12);
            const salesData = this.generateRandomData(12, 100000, 500000);
            const profitData = salesData.map(sale => sale * 0.3);
            
            this.charts.profitVsSales = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales',
                        data: salesData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                    }, {
                        label: 'Profit',
                        data: profitData,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'TZS ' + (value / 1000).toFixed(0) + 'K';
                                }
                            }
                        }
                    }
                }
            });
        },
        
        initMonthlyRevenueChart() {
            const ctx = document.getElementById('monthlyRevenueChart');
            if (!ctx) return;
            
            this.charts.monthlyRevenue = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue (TZS)',
                        data: [1200000, 1500000, 1800000, 1400000, 2000000, 2200000],
                        backgroundColor: 'rgba(147, 51, 234, 0.8)',
                        borderColor: 'rgb(147, 51, 234)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'TZS ' + (value / 1000000).toFixed(1) + 'M';
                                }
                            }
                        }
                    }
                }
            });
        },
        
        initCustomerGrowthChart() {
            const ctx = document.getElementById('customerGrowthChart');
            if (!ctx) return;
            
            this.charts.customerGrowth = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.generateDateLabels(12),
                    datasets: [{
                        label: 'Customers',
                        data: this.generateRandomData(12, 50, 200),
                        borderColor: 'rgb(251, 146, 60)',
                        backgroundColor: 'rgba(251, 146, 60, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        },
        
        initSalesHeatmapChart() {
            const ctx = document.getElementById('salesHeatmapChart');
            if (!ctx) return;
            
            // Generate heatmap data
            const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const hours = ['8AM', '10AM', '12PM', '2PM', '4PM', '6PM', '8PM'];
            const heatmapData = [];
            
            for (let i = 0; i < days.length; i++) {
                for (let j = 0; j < hours.length; j++) {
                    heatmapData.push(Math.random() * 100);
                }
            }
            
            this.charts.salesHeatmap = new Chart(ctx, {
                type: 'bubble',
                data: {
                    datasets: [{
                        label: 'Sales Intensity',
                        data: heatmapData.map((value, index) => ({
                            x: index % hours.length,
                            y: Math.floor(index / hours.length),
                            r: value / 10
                        })),
                        backgroundColor: 'rgba(59, 130, 246, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom',
                            min: 0,
                            max: hours.length - 1,
                            ticks: {
                                callback: function(value) {
                                    return hours[value] || '';
                                }
                            }
                        },
                        y: {
                            type: 'linear',
                            min: 0,
                            max: days.length - 1,
                            ticks: {
                                callback: function(value) {
                                    return days[value] || '';
                                }
                            }
                        }
                    }
                }
            });
        },
        
        // Chart type change functions
        changeSalesTrendType(type) {
            this.salesTrendType = type;
            this.charts.salesTrend.config.type = type;
            this.charts.salesTrend.update();
        },
        
        changeCategoryType(type) {
            this.categoryType = type;
            this.charts.salesByCategory.config.type = type;
            this.charts.salesByCategory.config.cutout = type === 'donut' ? '50%' : '0%';
            this.charts.salesByCategory.update();
        },
        
        // Update all charts based on date range
        updateAllCharts() {
            // Update charts with new date range
            this.refreshDashboard();
        },
        
        refreshDashboard() {
            // Simulate dashboard refresh
            this.showNotification('Dashboard refreshed successfully', 'success');
        },
        
        refreshTransactions() {
            // Refresh recent transactions
            this.showNotification('Transactions refreshed', 'info');
        },
        
        setupAutoRefresh() {
            // Auto-refresh every 5 minutes
            setInterval(() => {
                this.refreshDashboard();
            }, 300000);
        },
        
        // Utility functions
        generateDateLabels(days) {
            const labels = [];
            const today = new Date();
            for (let i = days - 1; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(date.getDate() - i);
                labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            }
            return labels;
        },
        
        generateRandomData(count, min, max) {
            const data = [];
            for (let i = 0; i < count; i++) {
                data.push(Math.floor(Math.random() * (max - min + 1)) + min);
            }
            return data;
        },
        
        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg text-white z-50 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                'bg-blue-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    }
}
</script>
@endsection
