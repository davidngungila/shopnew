@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="space-y-6" x-data="productsComponent()">
    <!-- Advanced Header with Stats -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Products</h1>
            <p class="text-gray-600 mt-1">Advanced inventory management system</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button @click="exportData()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Export</span>
            </button>
            <button @click="importData()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l3 3m-3-3v12"></path>
                </svg>
                <span>Import</span>
            </button>
            <a href="{{ route('products.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    <!-- Advanced Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Products</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalProducts ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>+12% from last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011 1h10a1 1 0 110 2H5a1 1 0 01-1-1V8a1 1 0 011-1zM6 12a1 1 0 00-1 1v4A1 1 0 001 1h8a1 1 0 001-1v-4a1 1 0 00-1-1H6z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">In Stock</p>
                    <p class="text-3xl font-bold mt-2">{{ $inStockProducts ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>85% availability</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Low Stock Alert</p>
                    <p class="text-3xl font-bold mt-2">{{ $lowStockProducts ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1 8a1 1 0 100-2 0v-1a1 1 0 100 2v1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Requires attention</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-orange-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Value</p>
                    <p class="text-3xl font-bold mt-2">TZS {{ number_format($totalValue ?? 0, 2) }}</p>
                    <div class="flex items-center mt-2 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                        </svg>
                        <span>+18% from last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662.662 0 00-.224 1.48 4.667 4.667 0 001.266 2.226 1.514 1.514 0 01-.213.294c-.133.13-.313.23-.527.31v1.09a3.37 3.37 0 001.562-.352c.386-.196.724-.47.99-.828.266-.357.448-.77.548-1.221.1-.45.15-.943.15-1.473V5a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676-.662.662.662 0 00-.224 1.48 4.667 4.667 0 001.266 2.226C.046.05.098.1.151.144v.093z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Advanced Filters</h2>
            <button @click="resetFilters()" class="text-gray-500 hover:text-gray-700 text-sm">Reset All</button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                <div class="relative">
                    <input type="text" x-model="filters.search" placeholder="Search by name, SKU, or description..." 
                           class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select x-model="filters.category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Warehouse</label>
                <select x-model="filters.warehouse" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses ?? [] as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                <div class="flex space-x-2">
                    <input type="number" x-model="filters.minPrice" placeholder="Min" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="self-center text-gray-500">-</span>
                    <input type="number" x-model="filters.maxPrice" placeholder="Max" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stock Status</label>
                <select x-model="filters.stockStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Advanced Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-4 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-700">View: List Only</span>
                <div class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-medium">
                    Table View
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-700">Per page:</span>
                <select x-model="perPage" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- Grid View -->
        <div x-show="viewMode === 'grid'" class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products ?? [] as $product)
                <div class="bg-white border border-gray-200 rounded-lg hover:shadow-lg transition-shadow duration-200 overflow-hidden group">
                    <div class="relative">
                        <img src="{{ $product->image_url ?? asset('placeholder-product.jpg') }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-48 object-cover">
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        @if($product->stock_quantity <= $product->low_stock_alert)
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Low Stock
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ \Illuminate\Support\Str::limit($product->description ?? '', 80) }}</p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</span>
                            <span class="text-xs text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-gray-900">${{ number_format($product->selling_price, 2) }}</span>
                                <div class="text-sm {{ $product->stock_quantity <= $product->low_stock_alert ? 'text-red-600' : 'text-green-600' }}">
                                    Stock: {{ $product->stock_quantity }} {{ $product->unit }}
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <button @click="quickEdit({{ $product->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-1.414a2 2 0 010-2.828L12.828 3H14a2 2 0 012 2v2m-2 0h-2"></path>
                                    </svg>
                                </button>
                                <button @click="quickDelete({{ $product->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No products found</p>
                    <p class="text-gray-400 text-sm mt-2">Try adjusting your filters or add a new product</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- List View -->
        <div x-show="viewMode === 'list'" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" @change="selectAll($event.target.checked)" class="rounded border-gray-300">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('name')">
                            Product <span x-text="getSortIcon('name')"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('sku')">
                            SKU <span x-text="getSortIcon('sku')"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('category')">
                            Category <span x-text="getSortIcon('category')"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('stock')">
                            Stock <span x-text="getSortIcon('stock')"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('price')">
                            Price (TZS) <span x-text="getSortIcon('price')"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products ?? [] as $product)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" :value="{{ $product->id }}" x-model="selectedProducts" class="rounded border-gray-300">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                    <img src="{{ $product->image_url ?? asset('placeholder-product.jpg') }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-8 h-8 object-cover rounded">
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($product->description ?? '', 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->sku ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium {{ $product->stock_quantity <= $product->low_stock_alert ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $product->stock_quantity }} {{ $product->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">TZS {{ number_format($product->selling_price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <!-- Preview Button -->
                                <button @click="showProductPreview({{ $product->id }})" class="text-purple-600 hover:text-purple-900" title="Preview">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <!-- Edit Button -->
                                <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <!-- Delete Button -->
                                <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No products found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Bulk Actions -->
        <div x-show="selectedProducts.length > 0" class="p-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700">
                    <span x-text="selectedProducts.length"></span> products selected
                </span>
                <div class="flex items-center space-x-2">
                    <button @click="bulkEdit()" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Bulk Edit</button>
                    <button @click="bulkDelete()" class="px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">Bulk Delete</button>
                    <button @click="bulkExport()" class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">Export Selected</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Preview Modal -->
    <div x-show="showPreview" x-cloak 
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
         @click.self="showPreview = false">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white"
             @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Product Preview</h3>
                <button @click="showPreview = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div x-show="previewProduct" class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                        <img x-show="previewProduct.image_url" :src="previewProduct.image_url" :alt="previewProduct.name" 
                             class="w-24 h-24 rounded-lg object-cover">
                        <svg x-show="!previewProduct.image_url" class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-semibold text-gray-900" x-text="previewProduct.name"></h4>
                        <p class="text-sm text-gray-500 mt-1">SKU: <span x-text="previewProduct.sku"></span></p>
                        <p class="text-sm text-gray-500">Category: <span x-text="previewProduct.category"></span></p>
                        <div class="mt-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                  :class="previewProduct.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                  x-text="previewProduct.is_active ? 'Active' : 'Inactive'"></span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Selling Price</p>
                        <p class="text-lg font-semibold text-gray-900">TZS <span x-text="previewProduct.selling_price"></span></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Cost Price</p>
                        <p class="text-lg font-semibold text-gray-900">TZS <span x-text="previewProduct.cost_price"></span></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Stock Quantity</p>
                        <p class="text-lg font-semibold" 
                           :class="previewProduct.stock_quantity <= previewProduct.low_stock_alert ? 'text-red-600' : 'text-green-600'"
                           x-text="previewProduct.stock_quantity + ' ' + previewProduct.unit"></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Low Stock Alert</p>
                        <p class="text-lg font-semibold text-gray-900" x-text="previewProduct.low_stock_alert + ' ' + previewProduct.unit"></p>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-sm text-gray-500 mb-2">Description</p>
                    <p class="text-gray-900" x-text="previewProduct.description || 'No description available'"></p>
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <a :href="'/products/' + previewProduct.id + '/edit'" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Edit Product
                    </a>
                    <button @click="showPreview = false" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
function productsComponent() {
    return {
        viewMode: 'list',
        perPage: 25,
        selectedProducts: [],
        showPreview: false,
        previewProduct: null,
        filters: {
            search: '',
            category: '',
            warehouse: '',
            minPrice: '',
            maxPrice: '',
            stockStatus: ''
        },
        sortField: 'name',
        sortDirection: 'asc',
        
        init() {
            // Initialize component
            console.log('Products component initialized');
        },
        
        showProductPreview(productId) {
            // Mock product data - in real app, this would fetch from API
            const products = @json($products ?? []);
            this.previewProduct = products.find(p => p.id == productId);
            if (this.previewProduct) {
                this.showPreview = true;
            }
        },
        
        resetFilters() {
            this.filters = {
                search: '',
                category: '',
                warehouse: '',
                minPrice: '',
                maxPrice: '',
                stockStatus: ''
            };
        },
        
        selectAll(checked) {
            if (checked) {
                this.selectedProducts = @json(collect($products ?? [])->pluck('id'));
            } else {
                this.selectedProducts = [];
            }
        },
        
        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'asc';
            }
        },
        
        getSortIcon(field) {
            if (this.sortField !== field) return '';
            return this.sortDirection === 'asc' ? '↑' : '↓';
        },
        
        exportData() {
            alert('Export functionality would download CSV/Excel file');
        },
        
        importData() {
            alert('Import functionality would open file upload dialog');
        },
        
        bulkEdit() {
            alert(`Bulk edit ${this.selectedProducts.length} products`);
        },
        
        bulkDelete() {
            if (confirm(`Are you sure you want to delete ${this.selectedProducts.length} products?`)) {
                // Bulk delete functionality
                console.log('Bulk delete products:', this.selectedProducts);
            }
        },
        
        bulkExport() {
            // Bulk export functionality
            console.log('Bulk export products:', this.selectedProducts);
        },
        
        exportData() {
            // Export all data
            console.log('Export all products');
        },
        
        importData() {
            // Import data functionality
            console.log('Import products');
        }
    }
}
</script>
@endsection

