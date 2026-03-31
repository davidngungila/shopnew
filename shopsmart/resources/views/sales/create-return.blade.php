@extends('layouts.app')

@section('title', 'Create Return')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Create New Return</h1>
            <p class="text-gray-600 mt-1">Process a sales return or stock movement return</p>
        </div>
        <a href="{{ route('sales.returns') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Back to Returns</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <form id="returnForm" method="POST" action="{{ route('sales.returns.store') }}">
            @csrf
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Return Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Return Type</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="return_type" value="sale" checked class="peer sr-only" onchange="toggleReturnType('sale')">
                            <div class="w-full p-4 border-2 rounded-lg peer-checked:border-[#009245] peer-checked:bg-green-50 hover:bg-gray-50 transition-all">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-gray-600 peer-checked:text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900">Sale Return</div>
                                        <div class="text-sm text-gray-500">Return items from a completed sale</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="return_type" value="stock_movement" class="peer sr-only" onchange="toggleReturnType('stock_movement')">
                            <div class="w-full p-4 border-2 rounded-lg peer-checked:border-[#009245] peer-checked:bg-green-50 hover:bg-gray-50 transition-all">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-gray-600 peer-checked:text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900">Stock Movement</div>
                                        <div class="text-sm text-gray-500">Direct stock return without sale reference</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Sale Selection (shown for sale returns) -->
                <div id="saleSelection" class="space-y-4">
                    <div>
                        <label for="sale_id" class="block text-sm font-medium text-gray-700 mb-2">Select Sale</label>
                        <select id="sale_id" name="sale_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <option value="">Choose a sale to return...</option>
                            @foreach($sales as $sale)
                            <option value="{{ $sale->id }}" data-customer="{{ $sale->customer->name ?? 'Walk-in Customer' }}" data-total="{{ $sale->total }}">
                                #{{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }} - {{ $sale->customer->name ?? 'Walk-in Customer' }} - TZS {{ number_format($sale->total, 0) }} ({{ $sale->created_at->format('M d, Y') }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Sale Details (shown when sale is selected) -->
                    <div id="saleDetails" class="hidden p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-2">Sale Details</h4>
                        <div id="saleDetailsContent"></div>
                    </div>
                </div>

                <!-- Product Selection (shown for stock movement returns) -->
                <div id="productSelection" class="hidden space-y-4">
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">Select Product</label>
                        <select id="product_id" name="product_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                            <option value="">Choose a product...</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" data-stock="{{ $product->stock_quantity }}" data-price="{{ $product->selling_price }}">
                                {{ $product->name }} - Stock: {{ $product->stock_quantity }} - TZS {{ number_format($product->selling_price, 0) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity to Return</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                </div>

                <!-- Refund Amount -->
                <div>
                    <label for="refund_amount" class="block text-sm font-medium text-gray-700 mb-2">Refund Amount (TZS)</label>
                    <input type="number" id="refund_amount" name="refund_amount" min="0" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                    <p class="text-sm text-gray-500 mt-1">Amount to refund to customer</p>
                </div>

                <!-- Customer (optional for stock movement returns) -->
                <div id="customerSelection" class="hidden">
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Customer (Optional)</label>
                    <select id="customer_id" name="customer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                        <option value="">Select customer (optional)</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Return Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Return Reason</label>
                    <textarea id="reason" name="reason" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" placeholder="Enter the reason for return..."></textarea>
                </div>

                <!-- Additional Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]" placeholder="Any additional notes..."></textarea>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('sales.returns') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 text-white rounded-lg transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                        Process Return
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function toggleReturnType(type) {
    const saleSelection = document.getElementById('saleSelection');
    const productSelection = document.getElementById('productSelection');
    const customerSelection = document.getElementById('customerSelection');
    const saleIdField = document.getElementById('sale_id');
    const productIdField = document.getElementById('product_id');
    const saleDetails = document.getElementById('saleDetails');
    
    if (type === 'sale') {
        saleSelection.classList.remove('hidden');
        productSelection.classList.add('hidden');
        customerSelection.classList.add('hidden');
        saleIdField.required = true;
        productIdField.required = false;
        
        // Clear product field when switching to sale return
        productIdField.value = '';
        saleDetails.classList.add('hidden');
    } else {
        saleSelection.classList.add('hidden');
        productSelection.classList.remove('hidden');
        customerSelection.classList.remove('hidden');
        saleIdField.required = false;
        productIdField.required = true;
        
        // Clear sale field when switching to stock movement return
        saleIdField.value = '';
        document.getElementById('refund_amount').value = '';
    }
}

// Handle sale selection
document.getElementById('sale_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const saleDetails = document.getElementById('saleDetails');
    const saleDetailsContent = document.getElementById('saleDetailsContent');
    
    if (this.value) {
        const customer = selectedOption.dataset.customer;
        const total = selectedOption.dataset.total;
        
        saleDetailsContent.innerHTML = `
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium">Customer:</span> ${customer}
                </div>
                <div>
                    <span class="font-medium">Total Amount:</span> TZS ${parseInt(total).toLocaleString()}
                </div>
            </div>
        `;
        saleDetails.classList.remove('hidden');
        
        // Set refund amount to sale total
        document.getElementById('refund_amount').value = total;
    } else {
        saleDetails.classList.add('hidden');
        document.getElementById('refund_amount').value = '';
    }
});

// Handle form submission with AJAX
document.getElementById('returnForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Processing...';
    
    // Get form data
    const formData = new FormData(this);
    
    // Debug: Log form data
    console.log('Return Type:', formData.get('return_type'));
    console.log('Sale ID:', formData.get('sale_id'));
    console.log('Product ID:', formData.get('product_id'));
    console.log('Quantity:', formData.get('quantity'));
    console.log('Reason:', formData.get('reason'));
    console.log('Refund Amount:', formData.get('refund_amount'));
    
    // Clear empty fields to avoid validation issues
    const returnType = formData.get('return_type');
    if (returnType === 'sale') {
        formData.delete('product_id');
        if (!formData.get('sale_id')) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select a sale to return.',
                confirmButtonColor: '#dc3545'
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            return;
        }
    } else if (returnType === 'stock_movement') {
        formData.delete('sale_id');
        if (!formData.get('product_id')) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select a product to return.',
                confirmButtonColor: '#dc3545'
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            return;
        }
    }
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw err;
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonColor: '#009245',
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                window.location.href = data.redirect_url;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'An error occurred while processing the return.',
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        let errorMessage = 'An unexpected error occurred. Please try again.';
        
        if (error.errors) {
            // Laravel validation errors
            const firstError = Object.values(error.errors)[0];
            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: errorMessage,
            confirmButtonColor: '#dc3545'
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>
@endsection
