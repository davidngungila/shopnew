@extends('layouts.app')

@section('title', 'Transfer Stock')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Transfer Stock</h1>
            <p class="text-gray-600 mt-1">Transfer inventory between warehouses</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('warehouses.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Warehouses</span>
            </a>
        </div>
    </div>

    <!-- Transfer Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Stock Transfer Details</h3>
        
        <form id="transferForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Warehouse</label>
                    <select name="from_warehouse_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select source warehouse</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Warehouse</label>
                    <select name="to_warehouse_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select destination warehouse</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                <select name="product_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select a product</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" data-stock="{{ $product->stock_quantity }}" data-price="{{ $product->selling_price }}">
                        {{ $product->name }} (Stock: {{ $product->stock_quantity }}) - TZS {{ number_format($product->selling_price, 2) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" name="quantity" required min="1" placeholder="Enter quantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Available stock: <span id="availableStock" class="font-semibold">0</span></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Value</label>
                    <input type="text" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" value="TZS 0.00">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea name="notes" rows="3" placeholder="Add any notes about this transfer" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    <span id="transferSummary">Ready to transfer</span>
                </div>
                <div class="space-x-3">
                    <button type="button" onclick="resetForm()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Reset
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <span>Transfer Stock</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Recent Transfers -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Stock Movements</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warehouse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse(\App\Models\StockMovement::with(['product', 'warehouse', 'user'])->latest()->take(10)->get() as $movement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $movement->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $movement->product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $movement->type === 'in' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $movement->type === 'out' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $movement->type === 'transfer' ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ ucfirst($movement->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                            {{ $movement->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $movement->warehouse->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $movement->user->name ?? 'System' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            <p class="text-lg font-medium text-gray-900">No recent movements</p>
                            <p class="text-sm text-gray-500 mt-1">Stock movements will appear here once you start transferring inventory</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.querySelector('select[name="product_id"]');
    const quantityInput = document.querySelector('input[name="quantity"]');
    const availableStockSpan = document.getElementById('availableStock');
    const totalValueInput = document.querySelector('input[readonly]');
    const transferSummary = document.getElementById('transferSummary');
    
    // Update available stock when product is selected
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stock = selectedOption.dataset.stock || 0;
        const price = selectedOption.dataset.price || 0;
        
        availableStockSpan.textContent = stock;
        updateTotalValue();
        updateTransferSummary();
    });
    
    // Update total value when quantity changes
    quantityInput.addEventListener('input', updateTotalValue);
    
    function updateTotalValue() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price || 0);
        const quantity = parseInt(quantityInput.value || 0);
        const totalValue = price * quantity;
        
        totalValueInput.value = 'TZS ' + totalValue.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    
    function updateTransferSummary() {
        const fromWarehouse = document.querySelector('select[name="from_warehouse_id"]').value;
        const toWarehouse = document.querySelector('select[name="to_warehouse_id"]').value;
        const product = productSelect.value;
        const quantity = quantityInput.value;
        
        if (fromWarehouse && toWarehouse && product && quantity) {
            transferSummary.textContent = `Ready to transfer ${quantity} units`;
        } else {
            transferSummary.textContent = 'Ready to transfer';
        }
    }
    
    // Update summary when any field changes
    document.querySelectorAll('#transferForm input, #transferForm select').forEach(field => {
        field.addEventListener('change', updateTransferSummary);
    });
});

// Form submission
document.getElementById('transferForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const quantity = parseInt(formData.get('quantity'));
    const availableStock = parseInt(document.getElementById('availableStock').textContent);
    
    if (quantity > availableStock) {
        Swal.fire({
            icon: 'warning',
            title: 'Insufficient Stock',
            text: `Only ${availableStock} units available. You cannot transfer ${quantity} units.`,
            confirmButtonColor: '#F59E0B'
        });
        return;
    }
    
    Swal.fire({
        title: 'Confirm Transfer',
        text: 'Are you sure you want to transfer this stock?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, transfer!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("warehouses.transfer-stock.post") }}', {
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
                        title: 'Transfer Successful!',
                        text: data.message,
                        confirmButtonColor: '#10B981'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Transfer Failed!',
                        text: data.error,
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred during transfer',
                    confirmButtonColor: '#EF4444'
                });
            });
        }
    });
});

function resetForm() {
    document.getElementById('transferForm').reset();
    document.getElementById('availableStock').textContent = '0';
    document.querySelector('input[readonly]').value = 'TZS 0.00';
    document.getElementById('transferSummary').textContent = 'Ready to transfer';
}
</script>
@endsection
