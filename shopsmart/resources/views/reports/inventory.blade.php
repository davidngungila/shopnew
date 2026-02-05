@extends('layouts.app')

@section('title', 'Inventory Reports')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Inventory Reports</h1>
            <p class="text-gray-600 mt-1">Stock levels and product analytics</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Export</button>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Products</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalProducts ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Stock Value</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($totalStockValue ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Low Stock Items</p>
            <p class="text-xl sm:text-2xl font-bold text-yellow-600 mt-2">{{ number_format($lowStockCount ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Out of Stock</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600 mt-2">{{ number_format($outOfStockCount ?? 0) }}</p>
        </div>
    </div>

    <!-- Products by Category -->
    @if(isset($productsByCategory) && $productsByCategory->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Products by Category</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($productsByCategory as $category)
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm font-medium text-gray-900">{{ $category->category->name ?? 'Uncategorized' }}</p>
                <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($category->count) }} products</p>
                <p class="text-xs text-gray-500 mt-1">Value: TZS {{ number_format($category->value ?? 0, 0) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Value</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products ?? [] as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                            <span class="{{ $product->stock_quantity <= 0 ? 'text-red-600' : ($product->stock_quantity <= $product->low_stock_alert ? 'text-yellow-600' : 'text-gray-900') }}">
                                {{ number_format($product->stock_quantity) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">TZS {{ number_format($product->stock_quantity * $product->purchase_price, 0) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No products found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

