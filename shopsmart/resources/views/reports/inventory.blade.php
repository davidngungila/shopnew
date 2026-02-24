@extends('layouts.app')

@section('title', 'Inventory Reports')

@section('content')
<div class="space-y-4 sm:space-y-6" x-data="inventoryReport()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Inventory Reports</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Comprehensive stock levels and product analytics</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <button @click="toggleView()" class="px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2 text-sm transition-colors">
                <svg x-show="viewMode === 'grid'" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="viewMode === 'list'" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span class="hidden sm:inline" x-text="viewMode === 'grid' ? 'List View' : 'Grid View'"></span>
            </button>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="px-3 sm:px-4 py-2 bg-[#009245] text-white rounded-lg hover:bg-[#007a38] flex items-center space-x-2 text-sm transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="hidden sm:inline">Export</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10">
                    <a href="{{ route('reports.inventory.pdf', request()->query()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span>Export PDF</span>
                    </a>
                    <a href="{{ route('reports.inventory') }}?export=excel&{{ http_build_query(request()->except('export')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Export Excel</span>
                    </a>
                    <a href="{{ route('reports.inventory') }}?export=csv&{{ http_build_query(request()->except('export')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Export CSV</span>
                    </a>
                </div>
            </div>
            <button onclick="window.print()" class="px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2 text-sm transition-colors">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span class="hidden sm:inline">Print</span>
            </button>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center space-x-2">
                <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <span>Advanced Filters & Search</span>
            </h2>
            <button @click="showAdvanced = !showAdvanced" class="text-sm text-[#009245] hover:text-[#007a38] flex items-center space-x-1">
                <span x-text="showAdvanced ? 'Hide Advanced' : 'Show Advanced'"></span>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': showAdvanced}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
        <form method="GET" action="{{ route('reports.inventory') }}" class="space-y-4" id="filterForm">
            <!-- Basic Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product, SKU, barcode..." 
                        class="w-full pl-10 pr-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]"
                        x-on:input.debounce.500ms="$dispatch('search')">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                
                <select name="category_id" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                    <option value="">All Categories</option>
                    @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>

                <select name="warehouse_id" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses ?? [] as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                    @endforeach
                </select>

                <select name="stock_status" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                    <option value="">All Stock Status</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>

            <!-- Advanced Filters -->
            <div x-show="showAdvanced" x-collapse class="space-y-4 pt-4 border-t border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Stock Quantity Range</label>
                        <div class="flex gap-2">
                            <input type="number" name="stock_min" value="{{ request('stock_min') }}" placeholder="Min" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <input type="number" name="stock_max" value="{{ request('stock_max') }}" placeholder="Max" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Cost Price Range (TSh)</label>
                        <div class="flex gap-2">
                            <input type="number" name="cost_min" value="{{ request('cost_min') }}" placeholder="Min" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <input type="number" name="cost_max" value="{{ request('cost_max') }}" placeholder="Max" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Selling Price Range (TSh)</label>
                        <div class="flex gap-2">
                            <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Min" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Max" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort_by" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="stock_quantity" {{ request('sort_by') == 'stock_quantity' ? 'selected' : '' }}>Stock Quantity</option>
                            <option value="cost_price" {{ request('sort_by') == 'cost_price' ? 'selected' : '' }}>Cost Price</option>
                            <option value="selling_price" {{ request('sort_by') == 'selling_price' ? 'selected' : '' }}>Selling Price</option>
                            <option value="stock_value" {{ request('sort_by') == 'stock_value' ? 'selected' : '' }}>Stock Value</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <select name="sort_order" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Items Per Page</label>
                        <select name="per_page" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            <option value="200" {{ request('per_page') == '200' ? 'selected' : '' }}>200</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="active_only" value="1" {{ request('active_only') ? 'checked' : '' }} 
                                class="w-4 h-4 text-[#009245] border-gray-300 rounded focus:ring-[#009245]">
                            <span class="text-sm text-gray-700">Active Products Only</span>
                        </label>
                    </div>

                    <div class="flex items-end">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="with_image" value="1" {{ request('with_image') ? 'checked' : '' }} 
                                class="w-4 h-4 text-[#009245] border-gray-300 rounded focus:ring-[#009245]">
                            <span class="text-sm text-gray-700">With Images Only</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-[#009245] text-white rounded-lg hover:bg-[#007a38] transition-colors font-semibold flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Apply Filters</span>
                </button>
                <a href="{{ route('reports.inventory') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center">Reset</a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
        <!-- Total Products -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm border border-purple-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-purple-600 font-medium">Total Products</p>
                    <p class="text-2xl sm:text-3xl font-bold text-purple-900 mt-2">{{ number_format($totalProducts ?? 0) }}</p>
                    <p class="text-xs text-purple-600 mt-1">{{ number_format($activeProducts ?? 0) }} active</p>
                </div>
                <div class="w-14 h-14 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Stock Value -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border border-blue-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-blue-600 font-medium">Stock Value (Cost)</p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-900 mt-2">TSh {{ number_format($totalStockValue ?? 0, 0) }}</p>
                    <p class="text-xs text-blue-600 mt-1">At cost price</p>
                </div>
                <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Potential Profit -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm border border-green-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-green-600 font-medium">Potential Profit</p>
                    <p class="text-2xl sm:text-3xl font-bold text-green-900 mt-2">TSh {{ number_format($potentialProfit ?? 0, 0) }}</p>
                    <p class="text-xs text-green-600 mt-1">If all sold</p>
                </div>
                <div class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl shadow-sm border border-yellow-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-yellow-600 font-medium">Low Stock Items</p>
                    <p class="text-2xl sm:text-3xl font-bold text-yellow-900 mt-2">{{ number_format($lowStockCount ?? 0) }}</p>
                    <p class="text-xs text-red-600 mt-1 font-semibold">{{ number_format($outOfStockCount ?? 0) }} out of stock</p>
                </div>
                <div class="w-14 h-14 bg-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Products by Category Chart -->
        @if(isset($productsByCategory) && $productsByCategory->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Products by Category</span>
            </h2>
            <div class="space-y-3">
                @php
                    $maxValue = $productsByCategory->max('value') ?? 1;
                @endphp
                @foreach($productsByCategory->take(8) as $category)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-900">{{ $category->category->name ?? 'Uncategorized' }}</span>
                        <span class="text-sm font-semibold text-gray-700">TSh {{ number_format($category->value ?? 0, 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-gradient-to-r from-[#009245] to-[#007a38] h-2.5 rounded-full transition-all duration-500" 
                             style="width: {{ ($category->value ?? 0) / $maxValue * 100 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($category->count) }} products</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Top Products by Value -->
        @if(isset($topProductsByValue) && $topProductsByValue->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                <svg class="w-5 h-5 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <span>Top Products by Stock Value</span>
            </h2>
            <div class="space-y-3">
                @foreach($topProductsByValue->take(8) as $index => $product)
                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-white rounded-lg hover:shadow-sm transition-shadow">
                    <div class="flex items-center space-x-3 flex-1">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#009245] to-[#007a38] rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($product->stock_quantity) }} units</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-gray-900 ml-3">TSh {{ number_format($product->stock_value ?? 0, 0) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Alerts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Low Stock Products -->
        @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl shadow-sm border border-yellow-200 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-yellow-900 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>Low Stock Alert</span>
                </h2>
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-900">{{ $lowStockProducts->count() }} items</span>
            </div>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($lowStockProducts->take(10) as $product)
                <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">Stock: {{ number_format($product->stock_quantity) }} / Alert: {{ number_format($product->low_stock_alert) }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">Low</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Out of Stock Products -->
        @if(isset($outOfStockProducts) && $outOfStockProducts->count() > 0)
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-sm border border-red-200 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-red-900 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Out of Stock</span>
                </h2>
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-200 text-red-900">{{ $outOfStockProducts->count() }} items</span>
            </div>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($outOfStockProducts->take(10) as $product)
                <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-200 text-red-800">Out</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center space-x-3">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Products List</h2>
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">{{ $products->total() ?? 0 }} total</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-xs sm:text-sm text-gray-500">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }}</span>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cost Price</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Selling Price</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Value</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products ?? [] as $product)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    @if($product->barcode)
                                    <div class="text-xs text-gray-500">Barcode: {{ $product->barcode }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $product->category->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 font-mono">{{ $product->sku ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <span class="text-sm font-semibold {{ $product->stock_quantity <= 0 ? 'text-red-600' : ($product->stock_quantity <= $product->low_stock_alert ? 'text-yellow-600' : 'text-gray-900') }}">
                                    {{ number_format($product->stock_quantity) }}
                                </span>
                                @if($product->stock_quantity <= 0)
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">Out</span>
                                @elseif($product->stock_quantity <= $product->low_stock_alert)
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Low</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-medium text-gray-900">TSh {{ number_format($product->cost_price, 0) }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-medium text-gray-900">TSh {{ number_format($product->selling_price, 0) }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-bold text-gray-900">TSh {{ number_format($product->stock_quantity * $product->cost_price, 0) }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('products.show', $product->id) }}" class="text-[#009245] hover:text-[#007a38]" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-gray-200">
            @forelse($products ?? [] as $product)
            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-3 flex-1">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover">
                        @else
                        <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">SKU: {{ $product->sku ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-base font-bold {{ $product->stock_quantity <= 0 ? 'text-red-600' : ($product->stock_quantity <= $product->low_stock_alert ? 'text-yellow-600' : 'text-gray-900') }}">
                            {{ number_format($product->stock_quantity) }}
                        </div>
                        <div class="text-xs text-gray-500">Stock</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-gray-500">Category:</span>
                        <div class="font-medium text-gray-900 mt-0.5">{{ $product->category->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Value:</span>
                        <div class="font-medium text-gray-900 mt-0.5">TSh {{ number_format($product->stock_quantity * $product->cost_price, 0) }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Cost:</span>
                        <div class="font-medium text-gray-900 mt-0.5">TSh {{ number_format($product->cost_price, 0) }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Price:</span>
                        <div class="font-medium text-gray-900 mt-0.5">TSh {{ number_format($product->selling_price, 0) }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($products) && $products->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $products->total() ?? 0 }}</span> results
                </div>
                <div class="overflow-x-auto">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function inventoryReport() {
        return {
            viewMode: 'list',
            showAdvanced: false,
            toggleView() {
                this.viewMode = this.viewMode === 'list' ? 'grid' : 'list';
            }
        }
    }
</script>
@endpush
@endsection
