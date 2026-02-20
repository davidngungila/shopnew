@extends('layouts.app')

@section('title', 'Edit Quotation')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Quotation #{{ $quotation->quotation_number }}</h1>
            <p class="text-gray-600 mt-1">Update quotation details</p>
        </div>
        <a href="{{ route('quotations.show', $quotation) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</a>
    </div>

    <form action="{{ route('quotations.update', $quotation) }}" method="POST" id="quotationForm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer & Date Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                            <select name="customer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Select Customer (Optional)</option>
                                @foreach($customers ?? [] as $customer)
                                <option value="{{ $customer->id }}" {{ $quotation->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Warehouse</label>
                            <select name="warehouse_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Select Warehouse (Optional)</option>
                                @foreach($warehouses ?? [] as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ $quotation->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quotation Date</label>
                            <input type="date" name="quotation_date" value="{{ $quotation->quotation_date->format('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                            <input type="date" name="expiry_date" value="{{ $quotation->expiry_date ? $quotation->expiry_date->format('Y-m-d') : '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="pending" {{ $quotation->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $quotation->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $quotation->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Products</h2>
                        <button type="button" onclick="addProductRow()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
                            Add Product
                        </button>
                    </div>
                    <div id="productsContainer" class="space-y-4">
                        <!-- Products will be loaded from existing quotation -->
                    </div>
                </div>

                <!-- Terms & Notes -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                            <textarea name="terms_conditions" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ $quotation->terms_conditions }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Internal Notes</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ $quotation->notes }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold" id="subtotal">$0.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Discount:</span>
                            <span class="font-semibold" id="discount">$0.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax (10%):</span>
                            <span class="font-semibold" id="tax">$0.00</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-3">
                            <span>Total:</span>
                            <span id="total">$0.00</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-6 px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold">
                        Update Quotation
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script>
    let productRowIndex = 0;
    const products = @json($products ?? []);
    const existingItems = @json($quotation->items ?? []);

    function addProductRow(product = null) {
        const container = document.getElementById('productsContainer');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 sm:grid-cols-12 gap-4 items-end border border-gray-200 rounded-lg p-4';
        row.id = `product-row-${productRowIndex}`;
        
        row.innerHTML = `
            <div class="sm:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                <select name="items[${productRowIndex}][product_id]" class="product-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required onchange="updateProductPrice(${productRowIndex}, this.value)">
                    <option value="">Select Product</option>
                    ${products.map(p => `<option value="${p.id}" data-price="${p.selling_price}" ${product && product.product_id == p.id ? 'selected' : ''}>${p.name} - $${parseFloat(p.selling_price).toFixed(2)}</option>`).join('')}
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <input type="number" name="items[${productRowIndex}][quantity]" value="${product ? product.quantity : 1}" min="1" required class="quantity-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" onchange="calculateRowTotal(${productRowIndex})">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                <input type="number" name="items[${productRowIndex}][unit_price]" value="${product ? product.unit_price : 0}" step="0.01" min="0" required class="price-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" onchange="calculateRowTotal(${productRowIndex})">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Discount</label>
                <input type="number" name="items[${productRowIndex}][discount]" value="${product ? product.discount : 0}" step="0.01" min="0" class="discount-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" onchange="calculateRowTotal(${productRowIndex})">
            </div>
            <div class="sm:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                <div class="row-total px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm font-semibold">$0.00</div>
            </div>
            <div class="sm:col-span-1">
                <button type="button" onclick="removeProductRow(${productRowIndex})" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 mt-6">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        container.appendChild(row);
        productRowIndex++;
        calculateTotals();
    }

    function removeProductRow(index) {
        document.getElementById(`product-row-${index}`).remove();
        calculateTotals();
    }

    function updateProductPrice(rowIndex, productId) {
        const option = document.querySelector(`#product-row-${rowIndex} .product-select option[value="${productId}"]`);
        if (option) {
            const price = parseFloat(option.dataset.price || 0);
            document.querySelector(`#product-row-${rowIndex} .price-input`).value = price.toFixed(2);
            calculateRowTotal(rowIndex);
        }
    }

    function calculateRowTotal(rowIndex) {
        const row = document.getElementById(`product-row-${rowIndex}`);
        if (!row) return;

        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const discount = parseFloat(row.querySelector('.discount-input').value) || 0;
        const total = (price * quantity) - discount;

        row.querySelector('.row-total').textContent = '$' + total.toFixed(2);
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        let totalDiscount = 0;

        document.querySelectorAll('[id^="product-row-"]').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const discount = parseFloat(row.querySelector('.discount-input').value) || 0;
            subtotal += (price * quantity) - discount;
            totalDiscount += discount;
        });

        const tax = subtotal * 0.10;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('discount').textContent = '$' + totalDiscount.toFixed(2);
        document.getElementById('tax').textContent = '$' + tax.toFixed(2);
        document.getElementById('total').textContent = '$' + total.toFixed(2);
    }

    // Load existing items
    document.addEventListener('DOMContentLoaded', function() {
        if (existingItems.length > 0) {
            existingItems.forEach(item => {
                addProductRow({
                    product_id: item.product_id,
                    quantity: item.quantity,
                    unit_price: item.unit_price,
                    discount: item.discount
                });
            });
        } else {
            addProductRow();
        }
    });
</script>
@endsection
@endsection











