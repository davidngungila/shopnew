@extends('layouts.app')

@section('title', 'Stock Movements')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Stock Movements</h1>
            <p class="text-gray-600 mt-1">Track all stock movements and inventory changes</p>
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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l6-6m0 0l6 6m-6-6v12"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-green-600">Stock In</p>
                    <p class="text-2xl font-bold text-green-900">{{ number_format($stockMovements->where('movement_type', 'in')->sum('quantity')) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-red-600">Stock Out</p>
                    <p class="text-2xl font-bold text-red-900">{{ number_format($stockMovements->where('movement_type', 'out')->sum('quantity')) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-blue-600">Transfers</p>
                    <p class="text-2xl font-bold text-blue-900">{{ number_format($stockMovements->where('movement_type', 'transfer')->sum('quantity')) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-purple-600">Adjustments</p>
                    <p class="text-2xl font-bold text-purple-900">{{ number_format($stockMovements->where('movement_type', 'adjustment')->sum('quantity')) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Movement Type</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="in">Stock In</option>
                    <option value="out">Stock Out</option>
                    <option value="transfer">Transfer</option>
                    <option value="adjustment">Adjustment</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Products</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Warehouse</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Warehouses</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stock Movements Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Stock Movements History</h3>
                <div class="flex items-center space-x-3">
                    <button onclick="exportMovements()" class="text-sm text-blue-600 hover:text-blue-800">Export</button>
                    <button onclick="refreshData()" class="text-sm text-gray-600 hover:text-gray-800">Refresh</button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warehouse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stockMovements as $movement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $movement->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $movement->product->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $movement->product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $movement->movement_type === 'in' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $movement->movement_type === 'out' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $movement->movement_type === 'transfer' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $movement->movement_type === 'adjustment' ? 'bg-purple-100 text-purple-800' : '' }}">
                                {{ ucfirst($movement->movement_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium
                                {{ $movement->movement_type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->movement_type === 'in' ? '+' : '-' }}{{ number_format($movement->quantity) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $movement->warehouse->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $movement->reference_type }} #{{ $movement->reference_id }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate">{{ $movement->notes ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $movement->user->name ?? 'System' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-lg font-medium text-gray-900">No stock movements found</p>
                            <p class="text-sm text-gray-500 mt-1">Stock movements will appear here when inventory changes occur</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="{{ $stockMovements->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                <a href="{{ $stockMovements->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $stockMovements->firstItem() }}</span> to <span class="font-medium">{{ $stockMovements->lastItem() }}</span> of <span class="font-medium">{{ $stockMovements->total() }}</span> movements
                    </p>
                </div>
                <div>
                    {{ $stockMovements->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button onclick="addStockIn()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Add Stock In</span>
            </button>
            <button onclick="addStockOut()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Add Stock Out</span>
            </button>
            <button onclick="createTransfer()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                <span>Create Transfer</span>
            </button>
        </div>
    </div>
</div>

<script>
function exportMovements() {
    window.location.href = '{{ url("stock-movements/export") }}';
}

function refreshData() {
    location.reload();
}

function addStockIn() {
    Swal.fire({
        title: 'Add Stock In',
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                    <select id="stockInProduct" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Select a product</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" id="stockInQuantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Enter quantity" min="1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="stockInNotes" class="w-full px-3 py-2 border border-gray-300 rounded-lg" rows="3" placeholder="Optional notes"></textarea>
                </div>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Add Stock',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const product = document.getElementById('stockInProduct').value;
            const quantity = document.getElementById('stockInQuantity').value;
            
            if (!product || !quantity || quantity <= 0) {
                Swal.showValidationMessage('Please select a product and enter a valid quantity');
                return false;
            }
            
            return { product, quantity, notes: document.getElementById('stockInNotes').value };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Send stock in request
            fetch('{{ url("stock-movements/add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    movement_type: 'in',
                    product_id: result.value.product,
                    quantity: result.value.quantity,
                    notes: result.value.notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Stock added successfully.',
                        confirmButtonColor: '#10B981'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error || 'Failed to add stock',
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

function addStockOut() {
    Swal.fire({
        title: 'Add Stock Out',
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                    <select id="stockOutProduct" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Select a product</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" id="stockOutQuantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Enter quantity" min="1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                    <select id="stockOutReason" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="sale">Sale</option>
                        <option value="damage">Damage</option>
                        <option value="loss">Loss</option>
                        <option value="return">Return</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="stockOutNotes" class="w-full px-3 py-2 border border-gray-300 rounded-lg" rows="3" placeholder="Optional notes"></textarea>
                </div>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Remove Stock',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const product = document.getElementById('stockOutProduct').value;
            const quantity = document.getElementById('stockOutQuantity').value;
            
            if (!product || !quantity || quantity <= 0) {
                Swal.showValidationMessage('Please select a product and enter a valid quantity');
                return false;
            }
            
            return { 
                product, 
                quantity, 
                reason: document.getElementById('stockOutReason').value,
                notes: document.getElementById('stockOutNotes').value 
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Send stock out request
            fetch('{{ url("stock-movements/add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    movement_type: 'out',
                    product_id: result.value.product,
                    quantity: result.value.quantity,
                    reason: result.value.reason,
                    notes: result.value.notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Stock removed successfully.',
                        confirmButtonColor: '#10B981'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error || 'Failed to remove stock',
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

function createTransfer() {
    Swal.fire({
        title: 'Create Transfer',
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                    <select id="transferProduct" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Select a product</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Warehouse</label>
                    <select id="fromWarehouse" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Select source warehouse</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Warehouse</label>
                    <select id="toWarehouse" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Select destination warehouse</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" id="transferQuantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Enter quantity" min="1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="transferNotes" class="w-full px-3 py-2 border border-gray-300 rounded-lg" rows="3" placeholder="Optional notes"></textarea>
                </div>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Create Transfer',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const product = document.getElementById('transferProduct').value;
            const fromWarehouse = document.getElementById('fromWarehouse').value;
            const toWarehouse = document.getElementById('toWarehouse').value;
            const quantity = document.getElementById('transferQuantity').value;
            
            if (!product || !fromWarehouse || !toWarehouse || !quantity || quantity <= 0) {
                Swal.showValidationMessage('Please fill all required fields');
                return false;
            }
            
            if (fromWarehouse === toWarehouse) {
                Swal.showValidationMessage('Source and destination warehouses must be different');
                return false;
            }
            
            return { 
                product, 
                fromWarehouse, 
                toWarehouse, 
                quantity,
                notes: document.getElementById('transferNotes').value 
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Send transfer request
            fetch('{{ url("stock-movements/add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    movement_type: 'transfer',
                    product_id: result.value.product,
                    from_warehouse_id: result.value.fromWarehouse,
                    to_warehouse_id: result.value.toWarehouse,
                    quantity: result.value.quantity,
                    notes: result.value.notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Transfer created successfully.',
                        confirmButtonColor: '#10B981'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error || 'Failed to create transfer',
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
