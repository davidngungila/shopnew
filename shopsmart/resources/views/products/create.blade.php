@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="space-y-6" x-data="productCreateComponent()">
    <!-- Advanced Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Product</h1>
            <p class="text-gray-600 mt-1">Advanced product management system</p>
        </div>
        <div class="flex items-center space-x-3">
            <button @click="showQuickGuide()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Quick Guide</span>
            </button>
            <a href="{{ route('products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-medium">Total Categories</p>
                    <p class="text-2xl font-bold mt-1">{{ count($categories ?? []) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011 1h10a1 1 0 110 2H5a1 1 0 01-1-1V8a1 1 0 011-1zM6 12a1 1 0 00-1 1v4a1 1 0 001 1h8a1 1 0 001-1v-4a1 1 0 00-1-1H6z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-xs font-medium">Warehouses</p>
                    <p class="text-2xl font-bold mt-1">{{ count($warehouses ?? []) }}</p>
                </div>
                <div class="w-10 h-10 bg-green-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 7a2 2 0 00-2-2h-4V3a3 3 0 00-3-3H7a3 3 0 00-3 3v2H2a2 2 0 00-2 2v11a2 2 0 002 2h16a2 2 0 002-2V7zM6 3a1 1 0 011-1h4a1 1 0 011 1v2H6V3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-xs font-medium">Low Stock Items</p>
                    <p class="text-2xl font-bold mt-1">{{ $lowStockCount ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-xs font-medium">Total Products</p>
                    <p class="text-2xl font-bold mt-1">{{ $totalProducts ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 00-2 2v6a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2a1 1 0 100-2 2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
            <div class="flex flex-wrap items-center gap-3">
                <button @click="openCategoryModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add Category</span>
                </button>
                <button @click="openWarehouseModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add Warehouse</span>
                </button>
                <button @click="viewStockMovements()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                    </svg>
                    <span>Stock Movements</span>
                </button>
                <button @click="viewLowStockAlerts()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Low Stock Alerts</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Advanced Product Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200">
            <div class="flex items-center space-x-4 px-6 py-4">
                <button @click="activeTab = 'basic'" :class="activeTab === 'basic' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700'" 
                        class="px-4 py-2 rounded-lg transition-colors">
                    Basic Info
                </button>
                <button @click="activeTab = 'pricing'" :class="activeTab === 'pricing' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700'" 
                        class="px-4 py-2 rounded-lg transition-colors">
                    Pricing
                </button>
                <button @click="activeTab = 'inventory'" :class="activeTab === 'inventory' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700'" 
                        class="px-4 py-2 rounded-lg transition-colors">
                    Inventory
                </button>
                <button @click="activeTab = 'advanced'" :class="activeTab === 'advanced' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700'" 
                        class="px-4 py-2 rounded-lg transition-colors">
                    Advanced
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('products.store') }}" class="p-6" enctype="multipart/form-data">
            @csrf

            <!-- Basic Information Tab -->
            <div x-show="activeTab === 'basic'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Enter product name">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Auto-generated if empty">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Barcode</label>
                        <input type="text" name="barcode" value="{{ old('barcode') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Scan or enter barcode">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit <span class="text-red-500">*</span></label>
                        <select name="unit" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select unit</option>
                            <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                            <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                            <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Enter product description">{{ old('description') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                        <div class="flex items-center space-x-4">
                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <input type="file" name="image" accept="image/*" class="hidden" id="image-upload">
                                <button type="button" onclick="document.getElementById('image-upload').click()"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    Choose Image
                                </button>
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF up to 10MB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Tab -->
            <div x-show="activeTab === 'pricing'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cost Price <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="cost_price" value="{{ old('cost_price') }}" step="0.01" min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Selling Price <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="selling_price" value="{{ old('selling_price') }}" step="0.01" min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Wholesale Price</label>
                        <input type="number" name="wholesale_price" value="{{ old('wholesale_price') }}" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Retail Price</label>
                        <input type="number" name="retail_price" value="{{ old('retail_price') }}" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                    </div>
                </div>

                <!-- Profit Margin Calculator -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Profit Margin Calculator</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Cost Price</p>
                            <p class="text-lg font-bold text-gray-900">$<span x-text="document.querySelector('input[name=cost_price]').value || '0.00'"></span></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Selling Price</p>
                            <p class="text-lg font-bold text-green-600">$<span x-text="document.querySelector('input[name=selling_price]').value || '0.00'"></span></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Profit Margin</p>
                            <p class="text-lg font-bold text-blue-600"><span x-text="calculateMargin()">0%</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Tab -->
            <div x-show="activeTab === 'inventory'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Stock Quantity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Low Stock Alert <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="low_stock_alert" value="{{ old('low_stock_alert', 10) }}" min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="10">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Warehouse</label>
                        <select name="warehouse_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select warehouse</option>
                            @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reorder Level</label>
                        <input type="number" name="reorder_level" value="{{ old('reorder_level', 5) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="5">
                    </div>
                </div>

                <!-- Stock Status Preview -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Stock Status Preview</h3>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            In Stock
                        </span>
                        <span class="text-sm text-gray-600">Current stock: <span x-text="document.querySelector('input[name=stock_quantity]').value || '0'"></span></p>
                        <span class="text-sm text-gray-600">Alert at: <span x-text="document.querySelector('input[name=low_stock_alert]').value || '10'"></span></p>
                    </div>
                </div>
            </div>

            <!-- Advanced Tab -->
            <div x-show="activeTab === 'advanced'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                        <select name="supplier_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select supplier</option>
                            @foreach($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <input type="text" name="brand" value="{{ old('brand') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Product brand">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                        <input type="number" name="weight" value="{{ old('weight') }}" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dimensions (L×W×H cm)</label>
                        <input type="text" name="dimensions" value="{{ old('dimensions') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="10×5×3">
                    </div>
                </div>

                <!-- Settings -->
                <div class="space-y-4">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="track_stock" value="1" {{ old('track_stock', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Track Stock</span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="allow_decimal" value="1" {{ old('allow_decimal') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Allow Decimal Quantities</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <button type="button" @click="saveDraft()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Save Draft
                    </button>
                    <button type="button" @click="previewProduct()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Preview
                    </button>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('products.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Create Product
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick Reports Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Quick Reports</h2>
            <button @click="showAllReports()" class="text-blue-600 hover:text-blue-700 text-sm">View All Reports</button>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <button @click="generateReport('sales')" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-900">Sales Reports</p>
                        <p class="text-xs text-gray-500">View sales analytics</p>
                    </div>
                </div>
            </button>

            <button @click="generateReport('purchase')" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-900">Purchase Reports</p>
                        <p class="text-xs text-gray-500">View purchase history</p>
                    </div>
                </div>
            </button>

            <button @click="generateReport('profit-loss')" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-900">Profit & Loss</p>
                        <p class="text-xs text-gray-500">View P&L statement</p>
                    </div>
                </div>
            </button>

            <button @click="generateReport('inventory')" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-900">Inventory Report</p>
                        <p class="text-xs text-gray-500">View stock levels</p>
                    </div>
                </div>
            </button>
        </div>
    </div>
</div>

<script>
function productCreateComponent() {
    return {
        activeTab: 'basic',
        
        init() {
            console.log('Product create component initialized');
        },
        
        showQuickGuide() {
            alert('Quick guide: Fill in the product details, set pricing, configure inventory, and click Create Product.');
        },
        
        openCategoryModal() {
            alert('Category modal would open here');
        },
        
        openWarehouseModal() {
            alert('Warehouse modal would open here');
        },
        
        viewStockMovements() {
            window.open('/stock-movements', '_blank');
        },
        
        viewLowStockAlerts() {
            window.open('/low-stock-alerts', '_blank');
        },
        
        calculateMargin() {
            const cost = parseFloat(document.querySelector('input[name=cost_price]').value) || 0;
            const selling = parseFloat(document.querySelector('input[name=selling_price]').value) || 0;
            if (cost === 0) return '0%';
            const margin = ((selling - cost) / cost * 100).toFixed(2);
            return margin + '%';
        },
        
        saveDraft() {
            alert('Draft saved successfully');
        },
        
        previewProduct() {
            alert('Product preview would show here');
        },
        
        generateReport(type) {
            const reports = {
                'sales': '/reports/sales',
                'purchase': '/reports/purchases',
                'profit-loss': '/financial-statements/profit-loss',
                'inventory': '/reports/inventory'
            };
            window.open(reports[type], '_blank');
        },
        
        showAllReports() {
            window.open('/reports', '_blank');
        }
    }
}
</script>
@endsection

