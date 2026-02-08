@extends('layouts.app')

@section('title', 'Point of Sale')

@section('content')
<div class="space-y-6" x-data="posApp()">
    <!-- Header with Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Today's Sales</p>
                    <p class="text-2xl font-bold text-gray-900" id="todaySales">TZS 0</p>
                </div>
                <div class="p-3 rounded-full" style="background-color: #00924520;">
                    <svg class="w-6 h-6" style="color: #009245;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Cart Items</p>
                    <p class="text-2xl font-bold text-gray-900" x-text="cart.length"></p>
                </div>
                <div class="p-3 rounded-full" style="background-color: #00924520;">
                    <svg class="w-6 h-6" style="color: #009245;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Cart Total</p>
                    <p class="text-2xl font-bold text-gray-900" x-text="formatCurrency(cartTotal)"></p>
                </div>
                <div class="p-3 rounded-full" style="background-color: #00924520;">
                    <svg class="w-6 h-6" style="color: #009245;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Available Products</p>
                    <p class="text-2xl font-bold text-gray-900" x-text="filteredProducts.length"></p>
                </div>
                <div class="p-3 rounded-full" style="background-color: #00924520;">
                    <svg class="w-6 h-6" style="color: #009245;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Selection -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Customer & Search Bar -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 space-y-4">
                <!-- Customer Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer (Optional)</label>
                    <select x-model="selectedCustomer" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                        <option value="">Walk-in Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search & Category Filter -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <input 
                            type="text" 
                            x-model="searchQuery"
                            @input="filterProducts()"
                            @keydown.enter.prevent="handleBarcodeSearch()"
                            placeholder="Search by name, SKU, or scan barcode..." 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                            id="productSearch"
                        >
                    </div>
                    <div>
                        <select x-model="selectedCategory" @change="filterProducts()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Products</h3>
                    <span class="text-sm text-gray-500" x-text="`Showing ${filteredProducts.length} products`"></span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="productsGrid">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div 
                            @click="addToCart(product)"
                            :class="{'opacity-50 cursor-not-allowed': product.track_stock && product.stock_quantity <= 0, 'cursor-pointer hover:shadow-lg': product.track_stock && product.stock_quantity > 0}"
                            class="border border-gray-200 rounded-lg p-3 transition-all duration-200"
                        >
                            <div class="aspect-square bg-gray-100 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                                <template x-if="product.image">
                                    <img :src="`/storage/${product.image}`" :alt="product.name" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!product.image">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </template>
                            </div>
                            <div class="text-sm font-medium text-gray-900 truncate" x-text="product.name"></div>
                            <div class="text-xs text-gray-500 mt-1" x-text="product.sku"></div>
                            <div class="flex items-center justify-between mt-2">
                                <div class="text-sm font-semibold" style="color: #009245;" x-text="`TZS ${formatNumber(product.selling_price)}`"></div>
                                <div class="text-xs" :class="product.track_stock && product.stock_quantity <= 0 ? 'text-red-600' : product.track_stock && product.stock_quantity <= product.low_stock_alert ? 'text-yellow-600' : 'text-gray-500'">
                                    <span x-show="product.track_stock" x-text="`Stock: ${product.stock_quantity}`"></span>
                                    <span x-show="!product.track_stock">In Stock</span>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="filteredProducts.length === 0" class="col-span-full text-center py-8 text-gray-500">
                        <p>No products found</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col" style="max-height: calc(100vh - 200px);">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Cart</h2>
                <button @click="clearCart()" class="text-sm text-red-600 hover:text-red-800">Clear</button>
            </div>
            
            <div class="flex-1 overflow-y-auto mb-4 space-y-2" id="cartItems">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate" x-text="item.name"></div>
                            <div class="text-xs text-gray-500" x-text="`${formatCurrency(item.selling_price)} x ${item.quantity}`"></div>
                            <div class="text-xs font-semibold mt-1" style="color: #009245;" x-text="`${formatCurrency(item.selling_price * item.quantity - (item.discount || 0))}`"></div>
                        </div>
                        <div class="flex items-center space-x-2 ml-2">
                            <button @click="updateQuantity(index, -1)" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm font-semibold">-</button>
                            <input 
                                type="number" 
                                x-model="item.quantity"
                                @change="updateCartItem(index)"
                                min="1"
                                class="w-12 text-center text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#009245]"
                            >
                            <button @click="updateQuantity(index, 1)" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm font-semibold">+</button>
                            <button @click="removeFromCart(index)" class="ml-2 text-red-600 hover:text-red-800 font-bold text-lg">×</button>
                        </div>
                    </div>
                </template>
                <div x-show="cart.length === 0" class="text-center py-8 text-gray-500">
                    <p>Cart is empty</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4 space-y-3">
                <!-- Discount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount (TZS)</label>
                    <input 
                        type="number" 
                        x-model="cartDiscount"
                        @input="updateTotals()"
                        min="0"
                        step="0.01"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                        placeholder="0.00"
                    >
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold" x-text="formatCurrency(cartSubtotal)"></span>
                    </div>
                    <div class="flex justify-between" x-show="cartDiscount > 0">
                        <span class="text-gray-600">Discount:</span>
                        <span class="font-semibold text-red-600" x-text="`-${formatCurrency(cartDiscount)}`"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax ({{ number_format($taxRate * 100, 0) }}%):</span>
                        <span class="font-semibold" x-text="formatCurrency(cartTax)"></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                        <span>Total:</span>
                        <span style="color: #009245;" x-text="formatCurrency(cartTotal)"></span>
                    </div>
                </div>

                <div class="space-y-2 mt-4">
                    <select x-model="paymentMethod" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                        <option value="cash">💵 Cash</option>
                        <option value="card">💳 Card</option>
                        <option value="mobile_money">📱 Mobile Money</option>
                        <option value="bank_transfer">🏦 Bank Transfer</option>
                        <option value="credit">📝 Credit</option>
                    </select>
                    
                    <button 
                        @click="completeSale()"
                        :disabled="cart.length === 0 || processing"
                        class="w-full px-4 py-3 text-white rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        style="background-color: #009245;"
                        :style="cart.length === 0 || processing ? 'background-color: #ccc;' : 'background-color: #009245;'"
                    >
                        <span x-show="!processing">Complete Sale</span>
                        <span x-show="processing">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function posApp() {
    return {
        products: [],
        filteredProducts: [],
        cart: [],
        searchQuery: '',
        selectedCategory: '',
        selectedCustomer: '',
        cartDiscount: 0,
        paymentMethod: 'cash',
        processing: false,
        taxRate: {{ $taxRate }},
        
        cartSubtotal: 0,
        cartTax: 0,
        cartTotal: 0,

        init() {
            this.loadProducts();
            this.loadTodaySales();
            // Focus search on load
            this.$nextTick(() => {
                document.getElementById('productSearch')?.focus();
            });
        },

        async loadProducts() {
            try {
                const response = await fetch('{{ route('products.index') }}?format=json');
                const data = await response.json();
                this.products = data.products || data || [];
                this.filteredProducts = this.products;
            } catch (error) {
                console.error('Error loading products:', error);
                // Fallback: try direct API
                try {
                    const response = await fetch('/api/products');
                    const data = await response.json();
                    this.products = Array.isArray(data) ? data : [];
                    this.filteredProducts = this.products;
                } catch (e) {
                    console.error('Fallback also failed:', e);
                }
            }
        },

        async loadTodaySales() {
            try {
                const response = await fetch('/api/sales/today');
                const data = await response.json();
                if (data.total) {
                    document.getElementById('todaySales').textContent = this.formatCurrency(data.total);
                }
            } catch (error) {
                // Ignore if endpoint doesn't exist
            }
        },

        filterProducts() {
            let filtered = this.products;
            
            // Category filter
            if (this.selectedCategory) {
                filtered = filtered.filter(p => p.category_id == this.selectedCategory);
            }
            
            // Search filter
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(p => 
                    p.name?.toLowerCase().includes(query) ||
                    p.sku?.toLowerCase().includes(query) ||
                    p.barcode?.toLowerCase().includes(query)
                );
            }
            
            this.filteredProducts = filtered;
        },

        handleBarcodeSearch() {
            if (!this.searchQuery) return;
            
            const product = this.products.find(p => 
                p.barcode?.toLowerCase() === this.searchQuery.toLowerCase() ||
                p.sku?.toLowerCase() === this.searchQuery.toLowerCase()
            );
            
            if (product) {
                this.addToCart(product);
                this.searchQuery = '';
                this.filterProducts();
            }
        },

        addToCart(product) {
            if (product.track_stock && product.stock_quantity <= 0) {
                alert('Product is out of stock');
                return;
            }
            
            const existing = this.cart.find(item => item.id === product.id);
            if (existing) {
                if (product.track_stock && existing.quantity >= product.stock_quantity) {
                    alert('Cannot add more. Available stock: ' + product.stock_quantity);
                    return;
                }
                existing.quantity++;
            } else {
                this.cart.push({
                    ...product,
                    quantity: 1,
                    discount: 0
                });
            }
            this.updateTotals();
        },

        updateQuantity(index, change) {
            const item = this.cart[index];
            const newQuantity = Math.max(1, item.quantity + change);
            
            if (item.track_stock && newQuantity > item.stock_quantity) {
                alert('Cannot add more. Available stock: ' + item.stock_quantity);
                return;
            }
            
            item.quantity = newQuantity;
            this.updateTotals();
        },

        updateCartItem(index) {
            const item = this.cart[index];
            if (item.quantity < 1) item.quantity = 1;
            if (item.track_stock && item.quantity > item.stock_quantity) {
                item.quantity = item.stock_quantity;
                alert('Maximum available stock: ' + item.stock_quantity);
            }
            this.updateTotals();
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
            this.updateTotals();
        },

        clearCart() {
            if (confirm('Clear all items from cart?')) {
                this.cart = [];
                this.cartDiscount = 0;
                this.updateTotals();
            }
        },

        updateTotals() {
            this.cartSubtotal = this.cart.reduce((sum, item) => {
                return sum + (item.selling_price * item.quantity) - (item.discount || 0);
            }, 0);
            
            const subtotalAfterDiscount = this.cartSubtotal - this.cartDiscount;
            this.cartTax = subtotalAfterDiscount * this.taxRate;
            this.cartTotal = subtotalAfterDiscount + this.cartTax;
        },

        async completeSale() {
            if (this.cart.length === 0) {
                alert('Cart is empty');
                return;
            }

            this.processing = true;

            try {
                const response = await fetch('{{ route('pos.complete') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        items: this.cart.map(item => ({
                            id: item.id,
                            quantity: item.quantity,
                            selling_price: item.selling_price,
                            discount: item.discount || 0
                        })),
                        customer_id: this.selectedCustomer || null,
                        discount: this.cartDiscount,
                        payment_method: this.paymentMethod
                    })
                });

                const data = await response.json();

                if (data.success && data.sale) {
                    // Open receipt in new window
                    window.open(`/sales/${data.sale.id}/print`, '_blank');
                    
                    // Clear cart
                    this.cart = [];
                    this.cartDiscount = 0;
                    this.selectedCustomer = '';
                    this.updateTotals();
                    this.loadTodaySales();
                    
                    // Show success
                    alert('Sale completed successfully! Receipt opened in new window.');
                } else {
                    alert(data.message || 'Error completing sale');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error completing sale: ' + error.message);
            } finally {
                this.processing = false;
            }
        },

        formatCurrency(amount) {
            return 'TZS ' + this.formatNumber(amount);
        },

        formatNumber(num) {
            return parseFloat(num || 0).toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }
    }
}
</script>
@endsection
