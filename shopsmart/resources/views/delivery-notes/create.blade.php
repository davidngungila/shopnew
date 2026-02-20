@extends('layouts.app')

@section('title', 'New Delivery Note')

@section('content')
<div class="max-w-5xl mx-auto" x-data="deliveryNoteForm()">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">New Delivery Note</h1>
        <p class="text-gray-600 mt-1">Create a delivery note for sales, purchases, or transfers</p>
    </div>

    <form action="{{ route('delivery-notes.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Delivery Type *</label>
                <select 
                    name="type" 
                    id="type" 
                    x-model="type"
                    @change="onTypeChange()"
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                    <option value="">Select type</option>
                    <option value="sale" {{ old('type') == 'sale' ? 'selected' : '' }}>Sale</option>
                    <option value="purchase" {{ old('type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                    <option value="transfer" {{ old('type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
                @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Delivery Date *</label>
                <input 
                    type="date" 
                    name="delivery_date" 
                    id="delivery_date" 
                    value="{{ old('delivery_date', date('Y-m-d')) }}" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                @error('delivery_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div x-show="type === 'sale'">
                <label for="sale_id" class="block text-sm font-medium text-gray-700 mb-2">Sale Order</label>
                <select 
                    name="sale_id" 
                    id="sale_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                    <option value="">Select sale (optional)</option>
                    @foreach($sales ?? [] as $sale)
                        <option value="{{ $sale->id }}" {{ old('sale_id') == $sale->id ? 'selected' : '' }}>
                            {{ $sale->invoice_number }} - {{ $sale->customer->name ?? 'Walk-in' }} ({{ number_format($sale->total, 0) }} TZS)
                        </option>
                    @endforeach
                </select>
                @error('sale_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div x-show="type === 'purchase'">
                <label for="purchase_id" class="block text-sm font-medium text-gray-700 mb-2">Purchase Order</label>
                <select 
                    name="purchase_id" 
                    id="purchase_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                    <option value="">Select purchase (optional)</option>
                    @foreach($purchases ?? [] as $purchase)
                        <option value="{{ $purchase->id }}" {{ old('purchase_id') == $purchase->id ? 'selected' : '' }}>
                            {{ $purchase->purchase_number }} - {{ $purchase->supplier->name ?? 'N/A' }} ({{ number_format($purchase->total, 0) }} TZS)
                        </option>
                    @endforeach
                </select>
                @error('purchase_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div x-show="type === 'sale' || type === 'transfer'">
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                <select 
                    name="customer_id" 
                    id="customer_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                    <option value="">Select customer (optional)</option>
                    @foreach($customers ?? [] as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div x-show="type === 'purchase'">
                <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                <select 
                    name="supplier_id" 
                    id="supplier_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                    <option value="">Select supplier (optional)</option>
                    @foreach($suppliers ?? [] as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">Delivery Address</label>
                <textarea 
                    name="delivery_address" 
                    id="delivery_address" 
                    rows="2" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="Enter delivery address..."
                >{{ old('delivery_address') }}</textarea>
                @error('delivery_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                <input 
                    type="text" 
                    name="contact_person" 
                    id="contact_person" 
                    value="{{ old('contact_person') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="Contact person name"
                >
                @error('contact_person')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                <input 
                    type="text" 
                    name="contact_phone" 
                    id="contact_phone" 
                    value="{{ old('contact_phone') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="+255 123 456 789"
                >
                @error('contact_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Items Section -->
        <div class="border-t border-gray-200 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Delivery Items</h2>
                <button 
                    type="button" 
                    @click="addItem()"
                    class="px-4 py-2 text-white rounded-lg flex items-center space-x-2 text-sm" 
                    style="background-color: #009245;" 
                    onmouseover="this.style.backgroundColor='#007a38'" 
                    onmouseout="this.style.backgroundColor='#009245'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add Item</span>
                </button>
            </div>

            <div class="space-y-4" id="items-container">
                <template x-for="(item, index) in items" :key="index">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 p-4 border border-gray-200 rounded-lg">
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                            <select 
                                :name="`items[${index}][product_id]`"
                                x-model="item.product_id"
                                @change="onProductChange(index)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] text-sm"
                            >
                                <option value="">Select product (optional)</option>
                                @php
                                    $products = \App\Models\Product::where('is_active', true)->orderBy('name')->get();
                                @endphp
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                            <input 
                                type="text" 
                                :name="`items[${index}][item_name]`"
                                x-model="item.item_name"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] text-sm"
                                placeholder="Item name"
                            >
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                            <input 
                                type="number" 
                                :name="`items[${index}][quantity]`"
                                x-model="item.quantity"
                                required
                                min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] text-sm"
                                placeholder="1"
                            >
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                            <input 
                                type="text" 
                                :name="`items[${index}][unit]`"
                                x-model="item.unit"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] text-sm"
                                placeholder="e.g., pcs, kg, box"
                            >
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                            <button 
                                type="button" 
                                @click="removeItem(index)"
                                class="w-full px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm"
                            >
                                Remove
                            </button>
                        </div>
                        <div class="md:col-span-12">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea 
                                :name="`items[${index}][description]`"
                                x-model="item.description"
                                rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] text-sm"
                                placeholder="Item description (optional)"
                            ></textarea>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="items.length === 0" class="text-center py-8 text-gray-500">
                <p>No items added. Click "Add Item" to start.</p>
            </div>
        </div>

        <!-- Notes -->
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea 
                name="notes" 
                id="notes" 
                rows="4" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                placeholder="Enter any additional notes..."
            >{{ old('notes') }}</textarea>
            @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('delivery-notes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-white rounded-lg font-semibold transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                Create Delivery Note
            </button>
        </div>
    </form>
</div>

<script>
function deliveryNoteForm() {
    return {
        type: '{{ old('type', '') }}',
        items: @json(old('items', [['product_id' => '', 'item_name' => '', 'quantity' => 1, 'unit' => '', 'description' => '']])),
        
        init() {
            if (this.items.length === 0) {
                this.addItem();
            }
        },
        
        onTypeChange() {
            // Clear related fields when type changes
            if (this.type !== 'sale') {
                document.getElementById('sale_id').value = '';
            }
            if (this.type !== 'purchase') {
                document.getElementById('purchase_id').value = '';
            }
        },
        
        addItem() {
            this.items.push({
                product_id: '',
                item_name: '',
                quantity: 1,
                unit: '',
                description: ''
            });
        },
        
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            } else {
                alert('At least one item is required.');
            }
        },
        
        onProductChange(index) {
            const productId = this.items[index].product_id;
            if (productId) {
                // You can fetch product name here if needed
                // For now, the user will enter the item name manually
            }
        }
    }
}
</script>
@endsection








