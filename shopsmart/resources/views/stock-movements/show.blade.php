@extends('layouts.app')

@section('title', 'Stock Movement Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Stock Movement Details</h1>
            <p class="text-gray-600 mt-1">View movement information</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('stock-movements.edit', $stockMovement) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit</span>
            </a>
            <a href="{{ route('stock-movements.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Movement Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Movement Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Product</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $stockMovement->product->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">SKU</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $stockMovement->product->sku ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Movement Type</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $stockMovement->type === 'in' || $stockMovement->type === 'return' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ strtoupper($stockMovement->type) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Quantity</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ number_format($stockMovement->quantity) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Warehouse</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $stockMovement->warehouse->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $stockMovement->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @if($stockMovement->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $stockMovement->notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Product Information -->
            @if($stockMovement->product)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Current Stock</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ number_format($stockMovement->product->stock_quantity) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Low Stock Alert</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($stockMovement->product->low_stock_alert) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Purchase Price</dt>
                        <dd class="mt-1 text-sm text-gray-900">TZS {{ number_format($stockMovement->product->purchase_price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Selling Price</dt>
                        <dd class="mt-1 text-sm text-gray-900">TZS {{ number_format($stockMovement->product->selling_price, 2) }}</dd>
                    </div>
                </dl>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('stock-movements.edit', $stockMovement) }}" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Movement</span>
                    </a>
                    <form method="POST" action="{{ route('stock-movements.destroy', $stockMovement) }}" onsubmit="return confirm('Are you sure you want to delete this movement?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Delete Movement</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Recorded By -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recorded By</h3>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr($stockMovement->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $stockMovement->user->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500">{{ $stockMovement->user->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection





