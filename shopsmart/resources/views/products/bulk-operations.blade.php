@extends('layouts.app')

@section('title', 'Bulk Operations - Products')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Bulk Operations</h1>
            <p class="text-gray-600 mt-1">Perform bulk operations on multiple products</p>
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

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-blue-900 mb-3">How to Use Bulk Operations</h2>
        <ol class="list-decimal list-inside space-y-2 text-blue-800">
            <li>Select products using the checkboxes in the table below</li>
            <li>Choose an action from the "Bulk Actions" section</li>
            <li>Configure action parameters (if applicable)</li>
            <li>Confirm the action to apply it to all selected products</li>
        </ol>
        <div class="mt-4 p-3 bg-blue-100 rounded-lg">
            <p class="text-sm text-blue-700">
                <strong>Note:</strong> Delete action cannot be undone. Make sure to backup your data before performing bulk delete operations.
            </p>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Select Products</h3>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        Selected: <span id="selectedCount" class="font-semibold">0</span> of {{ $products->total() }}
                    </span>
                    <button onclick="selectAll()" class="text-sm text-blue-600 hover:text-blue-800">Select All</button>
                    <button onclick="clearSelection()" class="text-sm text-gray-600 hover:text-gray-800">Clear</button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" 
                                   class="product-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   value="{{ $product->id }}"
                                   onchange="updateSelectedCount()">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TZS {{ number_format($product->selling_price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium {{ $product->stock_quantity <= $product->low_stock_alert ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->category->name ?? 'No category' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
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
                        Showing <span class="font-medium">{{ $products->firstItem() }}</span> to <span class="font-medium">{{ $products->lastItem() }}</span> of <span class="font-medium">{{ $products->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bulk Actions</h3>
        
        <!-- Status Actions -->
        <div class="mb-6">
            <h4 class="font-medium text-gray-900 mb-3">Status Actions</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button onclick="performBulkAction('activate')" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Activate Selected</span>
                </button>
                <button onclick="performBulkAction('deactivate')" 
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                    <span>Deactivate Selected</span>
                </button>
                <button onclick="performBulkAction('delete')" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span>Delete Selected</span>
                </button>
            </div>
        </div>

        <!-- Price Actions -->
        <div class="mb-6">
            <h4 class="font-medium text-gray-900 mb-3">Price Actions</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h5 class="font-medium text-gray-900 mb-2">Update by Percentage</h5>
                    <div class="flex items-center space-x-2">
                        <input type="number" id="pricePercentage" placeholder="10" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="text-gray-600">%</span>
                        <button onclick="updatePrice('percentage')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">Apply</button>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <h5 class="font-medium text-gray-900 mb-2">Set Fixed Price</h5>
                    <div class="flex items-center space-x-2">
                        <input type="number" id="fixedPrice" placeholder="1000" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="text-gray-600">TZS</span>
                        <button onclick="updatePrice('fixed')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">Apply</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Actions -->
        <div>
            <h4 class="font-medium text-gray-900 mb-3">Stock Actions</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h5 class="font-medium text-gray-900 mb-2">Add Stock</h5>
                    <div class="flex items-center space-x-2">
                        <input type="number" id="addStock" placeholder="10" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button onclick="updateStock('add')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">Add</button>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <h5 class="font-medium text-gray-900 mb-2">Subtract Stock</h5>
                    <div class="flex items-center space-x-2">
                        <input type="number" id="subtractStock" placeholder="10" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button onclick="updateStock('subtract')" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">Subtract</button>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <h5 class="font-medium text-gray-900 mb-2">Set Stock</h5>
                    <div class="flex items-center space-x-2">
                        <input type="number" id="setStock" placeholder="100" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button onclick="updateStock('set')" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">Set</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    document.getElementById('selectedCount').textContent = checkboxes.length;
}

function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    
    productCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateSelectedCount();
}

function selectAll() {
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    
    productCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    
    selectAllCheckbox.checked = true;
    updateSelectedCount();
}

function clearSelection() {
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    
    productCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    selectAllCheckbox.checked = false;
    updateSelectedCount();
}

function performBulkAction(action) {
    const selectedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Products Selected',
            text: 'Please select at least one product to perform this action.',
            confirmButtonColor: '#3B82F6'
        });
        return;
    }
    
    const productIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    const actionText = action === 'activate' ? 'activate' : action === 'deactivate' ? 'deactivate' : 'delete';
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Are you sure you want to ${actionText} ${selectedCheckboxes.length} product${selectedCheckboxes.length === 1 ? '' : 's'}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'delete' ? '#EF4444' : '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: `Yes, ${actionText}!`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('action', action);
            productIds.forEach(id => formData.append('products[]', id));
            
            fetch('{{ url("products/bulk-operations") }}', {
                method: 'POST',
                body: formData,
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
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error || 'Action failed',
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

function updatePrice(type) {
    const selectedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Products Selected',
            text: 'Please select at least one product to update prices.',
            confirmButtonColor: '#3B82F6'
        });
        return;
    }
    
    const value = type === 'percentage' ? document.getElementById('pricePercentage').value : document.getElementById('fixedPrice').value;
    
    if (!value || value <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Value',
            text: 'Please enter a valid value.',
            confirmButtonColor: '#3B82F6'
        });
        return;
    }
    
    const productIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Are you sure you want to update prices for ${selectedCheckboxes.length} product${selectedCheckboxes.length === 1 ? '' : 's'}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, update!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('action', 'update_price');
            formData.append('price_type', type);
            formData.append('price_value', value);
            productIds.forEach(id => formData.append('products[]', id));
            
            fetch('{{ url("products/bulk-operations") }}', {
                method: 'POST',
                body: formData,
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
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error || 'Action failed',
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

function updateStock(action) {
    const selectedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Products Selected',
            text: 'Please select at least one product to update stock.',
            confirmButtonColor: '#3B82F6'
        });
        return;
    }
    
    const value = document.getElementById(action + 'Stock').value;
    
    if (!value || value < 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Value',
            text: 'Please enter a valid stock value.',
            confirmButtonColor: '#3B82F6'
        });
        return;
    }
    
    const productIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Are you sure you want to update stock for ${selectedCheckboxes.length} product${selectedCheckboxes.length === 1 ? '' : 's'}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, update!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('action', 'update_stock');
            formData.append('stock_action', action);
            formData.append('stock_value', value);
            productIds.forEach(id => formData.append('products[]', id));
            
            fetch('{{ url("products/bulk-operations") }}', {
                method: 'POST',
                body: formData,
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
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error || 'Action failed',
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
