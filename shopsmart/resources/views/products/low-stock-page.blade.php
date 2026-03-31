@extends('layouts.app')

@section('title', 'Low Stock Alerts')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Low Stock Alerts</h1>
            <p class="text-gray-600 mt-1">Products that need restocking</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Products</span>
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-red-600">Low Stock Items</p>
                    <p class="text-2xl font-bold text-red-900">{{ $products->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-orange-600">Total Value</p>
                    <p class="text-2xl font-bold text-orange-900">TZS {{ number_format($products->sum(function($product) { return $product->stock_quantity * $product->selling_price; }), 0) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-yellow-600">Avg. Days to Stockout</p>
                    <p class="text-2xl font-bold text-yellow-900">3.5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Low Stock Products</h3>
                <div class="flex items-center space-x-3">
                    <button onclick="exportLowStock()" class="text-sm text-blue-600 hover:text-blue-800">Export</button>
                    <button onclick="refreshData()" class="text-sm text-gray-600 hover:text-gray-800">Refresh</button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alert Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $product->barcode ?? 'No barcode' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">{{ $product->sku }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-red-600">{{ $product->stock_quantity }}</span>
                            <span class="text-xs text-gray-500 ml-1">units</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-orange-600">{{ $product->low_stock_alert }}</span>
                            <span class="text-xs text-gray-500 ml-1">units</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TZS {{ number_format($product->selling_price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">TZS {{ number_format($product->stock_quantity * $product->selling_price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->category->name ?? 'No category' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                <span class="text-gray-300">|</span>
                                <button onclick="quickRestock({{ $product->id }})" class="text-green-600 hover:text-green-900">Restock</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="{{ $products->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                <a href="{{ $products->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $products->firstItem() }}</span> to <span class="font-medium">{{ $products->lastItem() }}</span> of <span class="font-medium">{{ $products->total() }}</span> low stock items
                    </p>
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button onclick="bulkRestock()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Bulk Restock</span>
            </button>
            <button onclick="createPurchaseOrder()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span>Create Purchase Order</span>
            </button>
            <button onclick="sendNotifications()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span>Send Notifications</span>
            </button>
        </div>
    </div>
</div>

<script>
function exportLowStock() {
    window.location.href = '{{ url("products/low-stock/export") }}';
}

function refreshData() {
    location.reload();
}

function quickRestock(productId) {
    Swal.fire({
        title: 'Quick Restock',
        html: `
            <div class="text-left">
                <label class="block text-sm font-medium text-gray-700 mb-2">Restock Quantity</label>
                <input type="number" id="restockQuantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Enter quantity" min="1">
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Restock',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const quantity = document.getElementById('restockQuantity').value;
            if (!quantity || quantity <= 0) {
                Swal.showValidationMessage('Please enter a valid quantity');
                return false;
            }
            return quantity;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Send restock request
            fetch(`/products/${productId}/restock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    quantity: result.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Product restocked successfully.',
                        confirmButtonColor: '#10B981'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error || 'Restock failed',
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred',
                    confirmButtonColor: '#EF4444'
                });
            });
        }
    });
}

function bulkRestock() {
    Swal.fire({
        title: 'Bulk Restock',
        html: `
            <div class="text-left">
                <label class="block text-sm font-medium text-gray-700 mb-2">Restock Quantity</label>
                <input type="number" id="bulkRestockQuantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Enter quantity for all items" min="1">
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Bulk Restock',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const quantity = document.getElementById('bulkRestockQuantity').value;
            if (!quantity || quantity <= 0) {
                Swal.showValidationMessage('Please enter a valid quantity');
                return false;
            }
            return quantity;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Send bulk restock request
            fetch('{{ url("products/bulk-restock") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    quantity: result.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        confirmButtonColor: '#10B981'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error || 'Bulk restock failed',
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred',
                    confirmButtonColor: '#EF4444'
                });
            });
        }
    });
}

function createPurchaseOrder() {
    Swal.fire({
        title: 'Create Purchase Order',
        text: 'This will create a purchase order for all low stock items.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Create Order',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create purchase order
            fetch('{{ url("purchase-orders/create-from-low-stock") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Purchase order created successfully.',
                        confirmButtonColor: '#10B981'
                    }).then(() => {
                        window.location.href = data.redirect_url;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error || 'Failed to create purchase order',
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred',
                    confirmButtonColor: '#EF4444'
                });
            });
        }
    });
}

function sendNotifications() {
    Swal.fire({
        title: 'Send Notifications',
        text: 'Send low stock notifications to suppliers and managers.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#8B5CF6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Send Notifications',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send notifications
            fetch('{{ url("products/send-low-stock-notifications") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        confirmButtonColor: '#10B981'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error || 'Failed to send notifications',
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred',
                    confirmButtonColor: '#EF4444'
                });
            });
        }
    });
}
</script>
@endsection
