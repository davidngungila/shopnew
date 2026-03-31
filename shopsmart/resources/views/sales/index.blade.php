@extends('layouts.app')

@section('title', 'All Sales')

@section('content')
<div class="space-y-6" x-data="salesComponent()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Sales Management</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Comprehensive sales tracking and analytics dashboard</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('pos.index') }}" class="px-3 sm:px-4 py-2 text-white rounded-lg flex items-center space-x-2 text-sm transition-all duration-200 hover:scale-105" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="hidden sm:inline">New Sale</span>
                <span class="sm:hidden">New</span>
            </a>
            <a href="{{ route('sales.invoices') }}" class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center space-x-2 text-sm transition-all duration-200 hover:scale-105">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="hidden sm:inline">Invoices</span>
                <span class="sm:hidden">Invoices</span>
            </a>
            <a href="{{ route('sales.returns') }}" class="px-3 sm:px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 flex items-center space-x-2 text-sm transition-all duration-200 hover:scale-105">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l4 4m0-4l-4 4m4-11v11"></path>
                </svg>
                <span class="hidden sm:inline">Returns</span>
                <span class="sm:hidden">Returns</span>
            </a>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
        <!-- Total Sales -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg border border-green-200 p-4 sm:p-6 transform transition-all duration-200 hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-green-700 font-medium">Total Sales</p>
                    <p class="text-xl sm:text-2xl font-bold text-green-900 mt-2">{{ number_format($totalSales ?? 0) }}</p>
                    <p class="text-xs text-green-600 mt-1">All time transactions</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg border border-blue-200 p-4 sm:p-6 transform transition-all duration-200 hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-blue-700 font-medium">Total Revenue</p>
                    <p class="text-xl sm:text-2xl font-bold text-blue-900 mt-2">
                        TZS {{ number_format($totalAmount ?? 0, 0) }}
                    </p>
                    <p class="text-xs text-blue-600 mt-1">Lifetime earnings</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg border border-purple-200 p-4 sm:p-6 transform transition-all duration-200 hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-purple-700 font-medium">This Month</p>
                    <p class="text-xl sm:text-2xl font-bold text-purple-900 mt-2">
                        TZS {{ number_format($thisMonthAmount ?? 0, 0) }}
                    </p>
                    <p class="text-xs text-purple-600 mt-1">{{ number_format($thisMonthSales ?? 0) }} sales</p>
                </div>
                <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Sales -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl shadow-lg border border-orange-200 p-4 sm:p-6 transform transition-all duration-200 hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-orange-700 font-medium">Today's Sales</p>
                    <p class="text-xl sm:text-2xl font-bold text-orange-900 mt-2">{{ number_format($todaySales ?? 0) }} orders</p>
                    <p class="text-xs text-orange-600 mt-1">TZS {{ number_format($todayAmount ?? 0, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 01-.707 0l-6.414-6.414A1 1 0 013 6.586V4z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4v16h18V4H3z"></path>
                </svg>
                Advanced Filters
            </h2>
            <div class="flex gap-1.5 sm:gap-2 flex-wrap">
                <button @click="setDateRange('today')" class="px-3 py-1.5 text-xs sm:text-sm bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-all duration-200 font-medium">Today</button>
                <button @click="setDateRange('week')" class="px-3 py-1.5 text-xs sm:text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-all duration-200 font-medium">This Week</button>
                <button @click="setDateRange('month')" class="px-3 py-1.5 text-xs sm:text-sm bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-all duration-200 font-medium">This Month</button>
                <button @click="setDateRange('year')" class="px-3 py-1.5 text-xs sm:text-sm bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-all duration-200 font-medium">This Year</button>
            </div>
        </div>
        
        <form method="GET" action="{{ route('sales.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 sm:gap-4">
                <div class="relative">
                    <label class="text-xs sm:text-sm font-medium text-gray-700 mb-1 block">Search</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice #, Customer, Product..." class="w-full px-3 py-2 pl-9 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <div>
                    <label class="text-xs sm:text-sm font-medium text-gray-700 mb-1 block">Status</label>
                    <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        <option value="">All Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>💰 Refunded</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs sm:text-sm font-medium text-gray-700 mb-1 block">Payment Method</label>
                    <select name="payment_method" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>💳 Card</option>
                        <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>📱 Mobile Money</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                        <option value="credit" {{ request('payment_method') == 'credit' ? 'selected' : '' }}>📋 Credit</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs sm:text-sm font-medium text-gray-700 mb-1 block">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from', $dateFrom ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                </div>
                <div>
                    <label class="text-xs sm:text-sm font-medium text-gray-700 mb-1 block">To Date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to', $dateTo ?? '') }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 text-sm sm:text-base text-white rounded-lg transition-all duration-200 hover:scale-105 font-medium shadow-lg" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 01-.707 0l-6.414-6.414A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Apply Filters
                </button>
                <a href="{{ route('sales.index') }}" class="w-full sm:w-auto px-6 py-2.5 text-sm sm:text-base bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200 font-medium text-center">
                    Clear All
                </a>
            </div>
        </form>
    </div>

    <!-- Sales Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Sales Trend -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2z"></path>
                </svg>
                Sales Trend
            </h3>
            <div class="h-48 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg flex items-center justify-center">
                <p class="text-gray-500 text-sm">📊 Sales chart will be displayed here</p>
            </div>
        </div>

        <!-- Payment Methods Breakdown -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3v-1a1 1 0 00-1-1H4a1 1 0 00-1 1v1a3 3 0 003 3z"></path>
                </svg>
                Payment Methods
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">💵</span>
                        <div>
                            <p class="font-medium text-gray-900">Cash</p>
                            <p class="text-xs text-gray-500">Most popular</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-700">{{ number_format($cashSales ?? 0) }}</p>
                        <p class="text-xs text-gray-500">transactions</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">💳</span>
                        <div>
                            <p class="font-medium text-gray-900">Card</p>
                            <p class="text-xs text-gray-500">Growing fast</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-700">{{ number_format($cardSales ?? 0) }}</p>
                        <p class="text-xs text-gray-500">transactions</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">📱</span>
                        <div>
                            <p class="font-medium text-gray-900">Mobile Money</p>
                            <p class="text-xs text-gray-500">Convenient</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-purple-700">{{ number_format($mobileMoneySales ?? 0) }}</p>
                        <p class="text-xs text-gray-500">transactions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Sales Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Recent Sales
                </h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">{{ $sales->total() ?? 0 }} total</span>
                    <button @click="toggleView()" class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200">
                        <span x-show="viewMode === 'table'">📱 Cards</span>
                        <span x-show="viewMode === 'cards'">📋 Table</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div x-show="viewMode === 'table'" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-700 uppercase tracking-wider">Invoice #</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-700 uppercase tracking-wider">Customer</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-700 uppercase tracking-wider">Items</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-700 uppercase tracking-wider">Payment</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs sm:text-sm font-medium text-gray-700 uppercase tracking-wider">Total</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs sm:text-sm font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales ?? [] as $sale)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm font-medium text-gray-900">#{{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
                            @if($sale->customer && $sale->customer->phone)
                            <div class="text-xs text-gray-500">{{ $sale->customer->phone }}</div>
                            @endif
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm text-gray-900">{{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('h:i A') }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm text-gray-900">{{ $sale->items->count() }} items</div>
                            @if($sale->items->count() > 0)
                            <div class="text-xs text-gray-500">{{ $sale->items->first()->product->name ?? 'Unknown' }} @if($sale->items->count() > 1) +{{ $sale->items->count() - 1 }} @endif</div>
                            @endif
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                @php
                                    $paymentIcons = [
                                        'cash' => '💵',
                                        'card' => '💳',
                                        'mobile_money' => '📱',
                                        'bank_transfer' => '🏦',
                                        'credit' => '📋'
                                    ];
                                    $icon = $paymentIcons[$sale->payment_method] ?? '💵';
                                @endphp
                                <span class="text-lg">{{ $icon }}</span>
                                <div>
                                    <div class="text-xs sm:text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $sale->payment_method) }}</div>
                                    @if($sale->payment_method === 'card' && $sale->card_last_four)
                                    <div class="text-xs text-gray-500">**** {{ $sale->card_last_four }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'refunded' => 'bg-orange-100 text-orange-800',
                                ];
                                $color = $statusColors[$sale->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize">
                                {{ $sale->status ?? 'pending' }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <div class="text-xs sm:text-sm font-semibold text-gray-900">TZS {{ number_format($sale->total, 0) }}</div>
                            @if($sale->discount > 0)
                            <div class="text-xs text-gray-500">Discount: TZS {{ number_format($sale->discount, 0) }}</div>
                            @endif
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2" x-data="{ open: false }">
                                @php
                                    $sale->load('payments');
                                    $totalPaid = $sale->payments->sum('amount');
                                    $balance = $sale->total - $totalPaid;
                                @endphp
                                @if($sale->payment_method === 'credit' && $balance > 0)
                                <button onclick="openPaymentModal({{ $sale->id }}, {{ $sale->total }}, {{ $totalPaid }}, {{ $balance }})" class="px-3 py-1.5 text-xs text-white rounded flex items-center space-x-1" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Pay</span>
                                </button>
                                @endif
                                <!-- Actions Dropdown -->
                                <div class="relative">
                                    <button @click="open = !open" class="p-2 text-gray-600 hover:text-gray-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                        <a href="{{ route('sales.show', $sale) }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span>View More</span>
                                        </a>
                                        <a href="{{ route('sales.print', $sale) }}" target="_blank" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                            <span>Download PDF</span>
                                        </a>
                                        <a href="{{ route('sales.pdf', $sale) }}" target="_blank" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span>View Invoice</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No sales found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or create a new sale.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($sales) && $sales->hasPages())
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-t border-gray-200">
            <div class="overflow-x-auto">
                {{ $sales->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 z-50 overflow-y-auto hidden" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Record Payment</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="paymentForm" onsubmit="submitPayment(event)">
                <input type="hidden" id="paymentSaleId" name="sale_id">
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-semibold" id="paymentTotal">TZS 0</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Total Paid:</span>
                            <span class="font-semibold" id="paymentPaid">TZS 0</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold border-t border-gray-200 pt-2">
                            <span>Balance:</span>
                            <span class="text-red-600" id="paymentBalance">TZS 0</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Amount (TZS)</label>
                        <input type="number" id="paymentAmount" name="amount" step="0.01" min="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select id="paymentMethodSelect" name="payment_method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                        <input type="date" id="paymentDate" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <textarea id="paymentNotes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePaymentModal()" class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 z-50 overflow-y-auto hidden" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Record Payment</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="paymentForm" onsubmit="submitPayment(event)">
                <input type="hidden" id="paymentSaleId" name="sale_id">
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-semibold" id="paymentTotal">TZS 0</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Total Paid:</span>
                            <span class="font-semibold" id="paymentPaid">TZS 0</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold border-t border-gray-200 pt-2">
                            <span>Balance:</span>
                            <span class="text-red-600" id="paymentBalance">TZS 0</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Amount (TZS)</label>
                        <input type="number" id="paymentAmount" name="amount" step="0.01" min="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select id="paymentMethodSelect" name="payment_method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                        <input type="date" id="paymentDate" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <textarea id="paymentNotes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePaymentModal()" class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Date Range Presets
    function setDateRange(range) {
        const today = new Date();
        let from, to;
        
        switch(range) {
            case 'today':
                from = to = today.toISOString().split('T')[0];
                break;
            case 'week':
                const weekStart = new Date(today);
                weekStart.setDate(today.getDate() - today.getDay());
                from = weekStart.toISOString().split('T')[0];
                to = today.toISOString().split('T')[0];
                break;
            case 'month':
                from = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                to = today.toISOString().split('T')[0];
                break;
            case 'year':
                from = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
                to = today.toISOString().split('T')[0];
                break;
        }
        
        document.getElementById('date_from').value = from;
        document.getElementById('date_to').value = to;
    }

    // Payment Modal Functions
    function openPaymentModal(saleId, total, paid, balance) {
        document.getElementById('paymentSaleId').value = saleId;
        document.getElementById('paymentTotal').textContent = 'TZS ' + parseFloat(total).toLocaleString('en-US', {maximumFractionDigits: 0});
        document.getElementById('paymentPaid').textContent = 'TZS ' + parseFloat(paid).toLocaleString('en-US', {maximumFractionDigits: 0});
        document.getElementById('paymentBalance').textContent = 'TZS ' + parseFloat(balance).toLocaleString('en-US', {maximumFractionDigits: 0});
        document.getElementById('paymentAmount').value = '';
        document.getElementById('paymentAmount').max = balance;
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('paymentForm').reset();
    }

    function submitPayment(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const saleId = document.getElementById('paymentSaleId').value;
        const data = {
            amount: parseFloat(formData.get('amount')),
            payment_method: formData.get('payment_method'),
            payment_date: formData.get('payment_date'),
            notes: formData.get('notes')
        };

        fetch(`/sales/${saleId}/record-payment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Payment recorded successfully!');
                closePaymentModal();
                location.reload();
            } else {
                alert(result.message || 'Error recording payment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error recording payment: ' + error.message);
        });
    }

    // Close modal on background click
    document.getElementById('paymentModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePaymentModal();
        }
    });
</script>
@endsection
