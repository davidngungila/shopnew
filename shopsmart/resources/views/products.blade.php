<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - ShopSmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&family=Roboto:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'DM Sans', 'Roboto', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
        }
        
        .product-card {
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .category-pill {
            transition: all 0.2s ease;
        }
        
        .category-pill:hover {
            transform: scale(1.05);
        }
        
        .category-pill.active {
            background-color: #009245;
            color: white;
        }
        
        [x-cloak] {
            display: none !important;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }
        
        .hero-pattern {
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(0, 146, 69, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        .price-slider {
            -webkit-appearance: none;
            appearance: none;
            background: transparent;
            cursor: pointer;
        }
        
        .price-slider::-webkit-slider-track {
            background: #e5e7eb;
            height: 6px;
            border-radius: 3px;
        }
        
        .price-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            background: #009245;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            cursor: pointer;
        }
        
        .price-slider::-moz-range-track {
            background: #e5e7eb;
            height: 6px;
            border-radius: 3px;
        }
        
        .price-slider::-moz-range-thumb {
            background: #009245;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            cursor: pointer;
            border: none;
        }
        
        .suggestion-item {
            transition: all 0.2s ease;
        }
        
        .suggestion-item:hover {
            background-color: #f0fdf4;
            padding-left: 12px;
        }
        
        .rating-stars {
            color: #fbbf24;
        }
        
        .sale-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body class="bg-gray-50" x-data="shopPage()">
    <!-- Advanced Header -->
    <header class="bg-white shadow-xl sticky top-0 z-50 border-b border-gray-100">
        <!-- Top Bar -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white py-2">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center text-xs sm:text-sm">
                    <div class="flex items-center space-x-4">
                        <span class="flex items-center">
                            <i class="fas fa-phone mr-1"></i>
                            +255 712 345 678
                        </span>
                        <span class="hidden sm:flex items-center">
                            <i class="fas fa-envelope mr-1"></i>
                            info@shopsmart.co.tz
                        </span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            <span x-text="currentTime"></span>
                        </span>
                        <span class="hidden sm:flex items-center">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Dar es Salaam, Tanzania
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center">
                                <img src="{{ asset('logo.png') }}" alt="ShopSmart" class="h-10 w-auto mr-3">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">ShopSmart</h1>
                                    <p class="text-xs text-gray-500 hidden sm:block">Your Trusted Shopping Partner</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-8">
                        <a href="/landing" class="text-gray-700 hover:text-green-600 font-medium transition">Home</a>
                        <a href="/products" class="text-green-600 font-medium transition border-b-2 border-green-600 pb-1">Shop</a>
                        <a href="/services" class="text-gray-700 hover:text-green-600 font-medium transition">Services</a>
                        <a href="/about" class="text-gray-700 hover:text-green-600 font-medium transition">About</a>
                        <a href="/contact" class="text-gray-700 hover:text-green-600 font-medium transition">Contact</a>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Cart -->
                        <button @click="toggleCart()" class="relative p-2 text-gray-700 hover:text-green-600 transition">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span x-show="cart.length > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" x-text="cart.length"></span>
                        </button>
                        <a href="/login" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Small Decorative Line -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 py-1">
        <div class="max-w-7xl mx-auto">
            <div class="h-0.5 bg-white opacity-50"></div>
        </div>
    </div>

    <!-- Page Header -->
    <section class="gradient-bg hero-pattern py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex text-sm text-gray-600 mb-6">
                <a href="/landing" class="hover:text-green-600 transition">Home</a>
                <span class="mx-2">/</span>
                <span class="text-green-600 font-medium">Shop</span>
            </nav>
            
            <!-- Page Title -->
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    <span class="text-green-600">Shop</span> Our Products
                </h1>
                <p class="text-xl text-gray-600">Discover our wide range of quality products at unbeatable prices</p>
            </div>
        </div>
    </section>

    <!-- Main Shop Content -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filters -->
                <aside class="w-full lg:w-64 flex-shrink-0">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Filters</h2>
                        
                        <!-- Search Bar -->
                        <div class="mb-6">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="searchQuery" 
                                    @input="searchProducts()" 
                                    @focus="showSuggestions = true"
                                    @blur="setTimeout(() => showSuggestions = false, 200)"
                                    placeholder="Search products..." 
                                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                >
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                
                                <!-- Search Suggestions -->
                                <div x-show="showSuggestions" x-cloak class="absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-60 overflow-y-auto">
                                    <template x-for="suggestion in searchSuggestions.slice(0, 8)" :key="suggestion">
                                        <div class="suggestion-item px-3 py-2 cursor-pointer flex items-center">
                                            <i class="fas fa-search text-gray-400 mr-2 text-sm"></i>
                                            <span class="text-sm" x-text="suggestion"></span>
                                        </div>
                                    </template>
                                    <div x-show="searchSuggestions.length === 0" class="px-3 py-2 text-gray-500 text-sm">
                                        Start typing to see suggestions...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3">Categories</h3>
                            <div class="space-y-2">
                                <template x-for="category in categories" :key="category.id">
                                    <button @click="selectedCategory = selectedCategory === category.id ? null : category.id" 
                                            :class="selectedCategory === category.id ? 'active' : 'bg-gray-100 text-gray-700'"
                                            class="category-pill w-full text-left px-3 py-2 rounded-lg font-medium text-sm transition flex items-center justify-between">
                                        <span class="flex items-center">
                                            <i :class="category.icon" class="mr-2 text-sm"></i>
                                            <span x-text="category.name"></span>
                                        </span>
                                        <span class="text-xs bg-gray-200 px-2 py-1 rounded-full" x-text="category.count"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3">Price Range</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>TZS <span x-text="priceRange.min"></span></span>
                                    <span>TZS <span x-text="priceRange.max"></span></span>
                                </div>
                                <input 
                                    type="range" 
                                    x-model="priceRange.min" 
                                    @input="applyPriceFilter()"
                                    min="0" 
                                    max="200000" 
                                    step="1000"
                                    class="price-slider w-full"
                                >
                                <input 
                                    type="range" 
                                    x-model="priceRange.max" 
                                    @input="applyPriceFilter()"
                                    min="0" 
                                    max="200000" 
                                    step="1000"
                                    class="price-slider w-full"
                                >
                            </div>
                        </div>

                        <!-- Brands -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3">Brands</h3>
                            <div class="space-y-2">
                                <template x-for="brand in brands" :key="brand">
                                    <label class="flex items-center">
                                        <input type="checkbox" :value="brand" x-model="selectedBrands" @change="applyFilters()" class="mr-2 text-green-600">
                                        <span class="text-sm text-gray-700" x-text="brand"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3">Rating</h3>
                            <div class="space-y-2">
                                <template x-for="rating in [5, 4, 3, 2, 1]" :key="rating">
                                    <button @click="selectedRating = selectedRating === rating ? null : rating" 
                                            :class="selectedRating === rating ? 'text-green-600' : 'text-gray-400'"
                                            class="flex items-center hover:text-green-600 transition">
                                        <template x-for="i in 5" :key="i">
                                            <i class="fas fa-star text-sm" :class="i <= rating ? '' : 'text-gray-300'"></i>
                                        </template>
                                        <span class="ml-2 text-sm">& up</span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        <button @click="clearAllFilters()" class="w-full bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition">
                            Clear All Filters
                        </button>
                    </div>
                </aside>

                <!-- Product Grid -->
                <main class="flex-1">
                    <!-- Quick Filters and Sorting -->
                    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <!-- Results Count -->
                            <div class="text-gray-600">
                                Showing <span class="font-semibold" x-text="filteredProducts.length"></span> products
                            </div>
                            
                            <!-- Sorting Options -->
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600">Sort by:</span>
                                <select x-model="sortBy" @change="sortProducts()" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                                    <option value="popularity">Popularity</option>
                                    <option value="newest">Newest</option>
                                    <option value="price-low">Price: Low → High</option>
                                    <option value="price-high">Price: High → Low</option>
                                    <option value="rating">Rating</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Product Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                        <template x-for="product in paginatedProducts" :key="product.id">
                            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden group">
                                <!-- Product Image -->
                                <div class="relative">
                                    <img :src="product.image" :alt="product.name" class="w-full h-48 object-cover">
                                    
                                    <!-- Sale Badge -->
                                    <div x-show="product.discount" class="sale-badge absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                                        -<span x-text="product.discount"></span>%
                                    </div>
                                    
                                    <!-- Hot Deal Badge -->
                                    <div x-show="product.hotDeal" class="absolute top-2 left-2 bg-orange-500 text-white px-2 py-1 rounded text-xs font-bold">
                                        HOT DEAL
                                    </div>
                                    
                                    <!-- Quick Actions -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <button @click="quickView(product)" class="bg-white text-gray-900 px-3 py-2 rounded-lg text-sm font-medium mr-2 hover:bg-gray-100 transition">
                                            <i class="fas fa-eye mr-1"></i>Quick View
                                        </button>
                                        <button @click="toggleWishlist(product)" class="bg-white text-gray-900 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Product Info -->
                                <div class="p-4">
                                    <!-- Product Name -->
                                    <h3 class="font-semibold text-lg text-gray-900 mb-2 line-clamp-2" x-text="product.name"></h3>
                                    
                                    <!-- Rating -->
                                    <div class="flex items-center mb-2">
                                        <div class="rating-stars">
                                            <template x-for="i in 5" :key="i">
                                                <i class="fas fa-star text-sm" :class="i <= Math.floor(product.rating) ? '' : 'text-gray-300'"></i>
                                            </template>
                                        </div>
                                        <span class="text-xs text-gray-500 ml-2">(<span x-text="product.reviews"></span>)</span>
                                    </div>
                                    
                                    <!-- Price -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="text-xl font-bold text-green-600">TZS <span x-text="product.price.toLocaleString()"></span></span>
                                            <span x-show="product.originalPrice" class="text-sm text-gray-400 line-through ml-2">TZS <span x-text="product.originalPrice.toLocaleString()"></span></span>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-box"></i> <span x-text="product.stock"></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Add to Cart Button -->
                                    <button @click="addToCart(product)" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                                        <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center items-center space-x-2">
                        <button @click="currentPage > 1 && currentPage--" 
                                :disabled="currentPage === 1"
                                class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <template x-for="page in totalPages" :key="page">
                            <button @click="currentPage = page" 
                                    :class="currentPage === page ? 'bg-green-600 text-white' : 'border border-gray-300 hover:bg-gray-50'"
                                    class="px-3 py-2 rounded-lg transition">
                                <span x-text="page"></span>
                            </button>
                        </template>
                        
                        <button @click="currentPage < totalPages && currentPage++" 
                                :disabled="currentPage === totalPages"
                                class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </main>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">You May Also Like</h2>
                <p class="text-xl text-gray-600">Discover more amazing products</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <template x-for="product in featuredProducts.slice(0, 4)" :key="'featured-' + product.id">
                    <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                        <img :src="product.image" :alt="product.name" class="w-full h-32 object-cover">
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 mb-2 line-clamp-1" x-text="product.name"></h4>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-green-600">TZS <span x-text="product.price.toLocaleString()"></span></span>
                                <button @click="addToCart(product)" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">
                                    <i class="fas fa-cart-plus text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- Shopping Cart Modal -->
    <div x-show="showCart" @click.away="showCart = false" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click.stop class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Shopping Cart</h3>
                    <button @click="showCart = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4 mb-6">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <img :src="item.image" :alt="item.name" class="w-16 h-16 object-cover rounded">
                                <div>
                                    <h4 class="font-semibold" x-text="item.name"></h4>
                                    <p class="text-gray-600">TZS <span x-text="item.price.toLocaleString()"></span></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button @click="updateQuantity(item.id, item.quantity - 1)" class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="w-8 text-center" x-text="item.quantity"></span>
                                <button @click="updateQuantity(item.id, item.quantity + 1)" class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                                <button @click="removeFromCart(item.id)" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-semibold">Total:</span>
                        <span class="text-2xl font-bold text-green-600">TZS <span x-text="cartTotal.toLocaleString()"></span></span>
                    </div>
                    <button @click="proceedToCheckout()" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick View Modal -->
    <div x-show="showQuickView" @click.away="showQuickView = false" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click.stop class="bg-white rounded-lg shadow-xl max-w-4xl w-full p-6 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Quick View</h3>
                    <button @click="showQuickView = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div x-show="selectedProduct" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <img :src="selectedProduct.image" :alt="selectedProduct.name" class="w-full rounded-lg">
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2" x-text="selectedProduct.name"></h2>
                        <div class="flex items-center mb-4">
                            <div class="rating-stars">
                                <template x-for="i in 5" :key="i">
                                    <i class="fas fa-star" :class="i <= Math.floor(selectedProduct.rating) ? 'text-yellow-400' : 'text-gray-300'"></i>
                                </template>
                            </div>
                            <span class="text-gray-500 ml-2">(<span x-text="selectedProduct.reviews"></span> reviews)</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-3xl font-bold text-green-600">TZS <span x-text="selectedProduct.price.toLocaleString()"></span></span>
                            <span x-show="selectedProduct.originalPrice" class="text-lg text-gray-400 line-through ml-2">TZS <span x-text="selectedProduct.originalPrice.toLocaleString()"></span></span>
                        </div>
                        <p class="text-gray-600 mb-6" x-text="selectedProduct.description"></p>
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="flex items-center">
                                <label class="mr-2">Quantity:</label>
                                <input type="number" x-model="quickViewQuantity" min="1" class="w-20 px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                        <button @click="addToCartFromQuickView()" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition mb-4">
                            <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                        </button>
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-truck mr-1"></i> Free delivery on orders over TZS 50,000
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function shopPage() {
            return {
                products: [],
                categories: [],
                brands: ['Samsung', 'Apple', 'Nike', 'Adidas', 'Sony', 'LG', 'Xiaomi', 'Huawei'],
                featuredProducts: [],
                cart: [],
                wishlist: [],
                searchQuery: '',
                searchSuggestions: [],
                sortBy: 'popularity',
                selectedCategory: null,
                selectedBrands: [],
                selectedRating: null,
                priceRange: { min: 0, max: 200000 },
                showCart: false,
                showQuickView: false,
                showSuggestions: false,
                selectedProduct: null,
                quickViewQuantity: 1,
                currentPage: 1,
                itemsPerPage: 12,
                currentTime: '',
                
                get filteredProducts() {
                    let filtered = this.products;
                    
                    // Search filter
                    if (this.searchQuery) {
                        filtered = filtered.filter(product => 
                            product.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            product.brand.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            product.description.toLowerCase().includes(this.searchQuery.toLowerCase())
                        );
                    }
                    
                    // Category filter
                    if (this.selectedCategory) {
                        filtered = filtered.filter(product => product.categoryId === this.selectedCategory);
                    }
                    
                    // Brand filter
                    if (this.selectedBrands.length > 0) {
                        filtered = filtered.filter(product => this.selectedBrands.includes(product.brand));
                    }
                    
                    // Rating filter
                    if (this.selectedRating) {
                        filtered = filtered.filter(product => product.rating >= this.selectedRating);
                    }
                    
                    // Price filter
                    filtered = filtered.filter(product => 
                        product.price >= this.priceRange.min && product.price <= this.priceRange.max
                    );
                    
                    return filtered;
                },
                
                get paginatedProducts() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredProducts.slice(start, end);
                },
                
                get totalPages() {
                    return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
                },
                
                get cartTotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },
                
                init() {
                    this.loadProducts();
                    this.loadCategories();
                    this.loadFeaturedProducts();
                    this.loadFromLocalStorage();
                    this.updateCurrentTime();
                },
                
                updateCurrentTime() {
                    const updateTime = () => {
                        const now = new Date();
                        this.currentTime = now.toLocaleString('en-US', { 
                            weekday: 'short', 
                            hour: '2-digit', 
                            minute: '2-digit',
                            hour12: true 
                        });
                    };
                    updateTime();
                    setInterval(updateTime, 60000);
                },
                
                loadProducts() {
                    this.products = [
                        {
                            id: 101,
                            name: 'Premium Wireless Headphones with Noise Cancellation',
                            description: 'High-quality wireless headphones with active noise cancellation and premium sound quality.',
                            price: 85000,
                            originalPrice: 120000,
                            discount: 29,
                            rating: 4.7,
                            reviews: 234,
                            stock: 45,
                            categoryId: 1,
                            brand: 'Sony',
                            image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=200&fit=crop',
                            hotDeal: false,
                            createdAt: '2024-01-15'
                        },
                        {
                            id: 102,
                            name: 'Smart Fitness Watch Pro Max',
                            description: 'Advanced fitness tracking with heart rate monitor, GPS, and 7-day battery life.',
                            price: 145000,
                            originalPrice: 189000,
                            discount: 23,
                            rating: 4.8,
                            reviews: 567,
                            stock: 23,
                            categoryId: 1,
                            brand: 'Apple',
                            image: 'https://images.unsplash.com/photo-1523275335684-e346ac5d7ff1?w=300&h=200&fit=crop',
                            hotDeal: true,
                            createdAt: '2024-01-20'
                        },
                        {
                            id: 103,
                            name: 'Organic Tanzanian Coffee Beans 1kg',
                            description: 'Premium arabica coffee beans sourced directly from Tanzanian highlands.',
                            price: 35000,
                            rating: 4.9,
                            reviews: 892,
                            stock: 156,
                            categoryId: 2,
                            brand: 'Local Roasters',
                            image: 'https://images.unsplash.com/photo-1495474472287-4d2b2c0b723b?w=300&h=200&fit=crop',
                            hotDeal: false,
                            createdAt: '2024-01-10'
                        },
                        {
                            id: 104,
                            name: 'Professional Yoga Mat Premium',
                            description: 'Extra thick, non-slip yoga mat with carrying strap and alignment markers.',
                            price: 28000,
                            originalPrice: 35000,
                            discount: 20,
                            rating: 4.6,
                            reviews: 445,
                            stock: 89,
                            categoryId: 3,
                            brand: 'Nike',
                            image: 'https://images.unsplash.com/photo-1548610393-6db9522e1d0d?w=300&h=200&fit=crop',
                            hotDeal: false,
                            createdAt: '2024-01-12'
                        },
                        {
                            id: 105,
                            name: 'Stainless Steel Water Bottle 1L',
                            description: 'Insulated stainless steel water bottle keeps drinks cold for 24 hours.',
                            price: 18000,
                            rating: 4.5,
                            reviews: 234,
                            stock: 234,
                            categoryId: 3,
                            brand: 'Adidas',
                            image: 'https://images.unsplash.com/photo-1602143407159-36813c0ea9c3?w=300&h=200&fit=crop',
                            hotDeal: false,
                            createdAt: '2024-01-18'
                        },
                        {
                            id: 106,
                            name: 'Wireless Gaming Mouse RGB',
                            description: 'High-precision gaming mouse with customizable RGB lighting and programmable buttons.',
                            price: 42000,
                            originalPrice: 55000,
                            discount: 24,
                            rating: 4.7,
                            reviews: 178,
                            stock: 67,
                            categoryId: 1,
                            brand: 'LG',
                            image: 'https://images.unsplash.com/photo-1527864550419-7fd0869c525d?w=300&h=200&fit=crop',
                            hotDeal: true,
                            createdAt: '2024-01-22'
                        },
                        {
                            id: 107,
                            name: 'Ceramic Plant Pot Set of 3',
                            description: 'Beautiful ceramic plant pots with drainage holes and saucers.',
                            price: 22000,
                            rating: 4.4,
                            reviews: 123,
                            stock: 45,
                            categoryId: 4,
                            brand: 'HomeDecor',
                            image: 'https://images.unsplash.com/photo-1485955900006-10f4d1d914e3?w=300&h=200&fit=crop',
                            hotDeal: false,
                            createdAt: '2024-01-25'
                        },
                        {
                            id: 108,
                            name: 'LED Desk Lamp with USB Charging',
                            description: 'Modern LED desk lamp with adjustable brightness and built-in USB charger.',
                            price: 38000,
                            rating: 4.6,
                            reviews: 289,
                            stock: 78,
                            categoryId: 4,
                            brand: 'Xiaomi',
                            image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=200&fit=crop',
                            hotDeal: false,
                            createdAt: '2024-01-28'
                        },
                        {
                            id: 109,
                            name: 'Running Shoes Air Max',
                            description: 'Lightweight running shoes with responsive cushioning and breathable mesh upper.',
                            price: 95000,
                            originalPrice: 120000,
                            discount: 21,
                            rating: 4.8,
                            reviews: 456,
                            stock: 34,
                            categoryId: 5,
                            brand: 'Nike',
                            image: 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=300&h=200&fit=crop',
                            hotDeal: true,
                            createdAt: '2024-01-30'
                        },
                        {
                            id: 110,
                            name: 'Cotton T-Shirt Premium Collection',
                            description: '100% organic cotton t-shirts with modern designs and comfortable fit.',
                            price: 15000,
                            rating: 4.5,
                            reviews: 678,
                            stock: 234,
                            categoryId: 5,
                            brand: 'Adidas',
                            image: 'https://images.unsplash.com/photo-1521572163464-ba82c48a726d?w=300&h=200&fit=crop',
                            hotDeal: false,
                            createdAt: '2024-02-01'
                        },
                        {
                            id: 111,
                            name: 'Bluetooth Portable Speaker',
                            description: 'Waterproof portable speaker with 360° sound and 12-hour battery life.',
                            price: 55000,
                            originalPrice: 75000,
                            discount: 27,
                            rating: 4.7,
                            reviews: 345,
                            stock: 56,
                            categoryId: 1,
                            brand: 'Samsung',
                            image: 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=300&h=200&fit=crop',
                            hotDeal: true,
                            createdAt: '2024-02-03'
                        },
                        {
                            id: 112,
                            name: 'Kitchen Knife Set Professional',
                            description: '6-piece stainless steel knife set with wooden block and sharpener.',
                            price: 68000,
                            rating: 4.9,
                            reviews: 234,
                            stock: 28,
                            categoryId: 4,
                            brand: 'ProChef',
                            image: 'https://images.unsplash.com/photo-1556909114-f6e7a7400bba?w=300&h=200&fit=crop',
                            hotDeal: false,
                            createdAt: '2024-02-05'
                        },
                        {
                            id: 113,
                            name: 'Wireless Charging Pad Fast',
                            description: '15W fast wireless charging pad compatible with all Qi-enabled devices.',
                            price: 25000,
                            rating: 4.4,
                            reviews: 567,
                            stock: 145,
                            categoryId: 1,
                            brand: 'Huawei',
                            image: 'https://images.unsplash.com/photo-1586953208448-30d1d4b5cd0b?w=300&h=200&fit=crop',
                            hotDeal: false,
                            createdAt: '2024-02-07'
                        },
                        {
                            id: 114,
                            name: 'Organic Honey Raw 500g',
                            description: 'Pure raw honey from Tanzanian forests, unprocessed and natural.',
                            price: 18000,
                            rating: 4.8,
                            reviews: 892,
                            stock: 167,
                            categoryId: 2,
                            brand: 'Nature\'s Best',
                            image: 'https://images.unsplash.com/photo-1587049358235-25e2e0b4e4b6?w=300&h=200&fit=crop',
                            hotDeal: false,
                            createdAt: '2024-02-09'
                        },
                        {
                            id: 115,
                            name: 'Resistance Bands Set Complete',
                            description: '5-piece resistance band set with different resistance levels for full body workout.',
                            price: 22000,
                            originalPrice: 30000,
                            discount: 27,
                            rating: 4.6,
                            reviews: 234,
                            stock: 89,
                            categoryId: 3,
                            brand: 'FitGear',
                            image: 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=300&h=200&fit=crop',
                            hotDeal: true,
                            createdAt: '2024-02-11'
                        }
                    ];
                },
                
                loadCategories() {
                    this.categories = [
                        { id: 1, name: 'Electronics', icon: 'fas fa-laptop', count: 245 },
                        { id: 2, name: 'Food & Beverages', icon: 'fas fa-coffee', count: 189 },
                        { id: 3, name: 'Sports & Fitness', icon: 'fas fa-dumbbell', count: 156 },
                        { id: 4, name: 'Home & Living', icon: 'fas fa-home', count: 234 },
                        { id: 5, name: 'Fashion', icon: 'fas fa-tshirt', count: 428 }
                    ];
                },
                
                loadFeaturedProducts() {
                    this.featuredProducts = this.products.filter(p => p.rating >= 4.7).slice(0, 8);
                },
                
                searchProducts() {
                    if (this.searchQuery.length > 2) {
                        this.generateSearchSuggestions(this.searchQuery);
                    } else {
                        this.searchSuggestions = [];
                    }
                    this.currentPage = 1;
                },
                
                generateSearchSuggestions(query) {
                    const suggestions = [];
                    const lowerQuery = query.toLowerCase();
                    
                    this.products.forEach(product => {
                        if (product.name.toLowerCase().includes(lowerQuery)) {
                            suggestions.push(product.name);
                        }
                        if (product.brand.toLowerCase().includes(lowerQuery)) {
                            suggestions.push(product.brand);
                        }
                    });
                    
                    this.searchSuggestions = [...new Set(suggestions)].slice(0, 8);
                },
                
                sortProducts() {
                    this.currentPage = 1;
                },
                
                applyFilters() {
                    this.currentPage = 1;
                },
                
                applyPriceFilter() {
                    this.currentPage = 1;
                },
                
                clearAllFilters() {
                    this.selectedCategory = null;
                    this.selectedBrands = [];
                    this.selectedRating = null;
                    this.priceRange = { min: 0, max: 200000 };
                    this.searchQuery = '';
                    this.searchSuggestions = [];
                    this.currentPage = 1;
                },
                
                addToCart(product) {
                    const existingItem = this.cart.find(item => item.id === product.id);
                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        this.cart.push({
                            ...product,
                            quantity: 1
                        });
                    }
                    this.saveToLocalStorage();
                    this.showNotification('Product added to cart!', 'success');
                },
                
                addToCartFromQuickView() {
                    this.addToCart({
                        ...this.selectedProduct,
                        quantity: this.quickViewQuantity
                    });
                    this.showQuickView = false;
                    this.quickViewQuantity = 1;
                },
                
                updateQuantity(productId, quantity) {
                    if (quantity <= 0) {
                        this.removeFromCart(productId);
                    } else {
                        const item = this.cart.find(item => item.id === productId);
                        if (item) {
                            item.quantity = quantity;
                            this.saveToLocalStorage();
                        }
                    }
                },
                
                toggleCart() {
                    this.showCart = !this.showCart;
                },
                
                proceedToCheckout() {
                    this.showCart = false;
                    this.showNotification('Proceeding to checkout...', 'info');
                },
                
                quickView(product) {
                    this.selectedProduct = product;
                    this.showQuickView = true;
                },
                
                toggleWishlist(product) {
                    const index = this.wishlist.findIndex(item => item.id === product.id);
                    if (index > -1) {
                        this.wishlist.splice(index, 1);
                        this.showNotification('Removed from wishlist', 'info');
                    } else {
                        this.wishlist.push(product);
                        this.showNotification('Added to wishlist!', 'success');
                    }
                },
                
                saveToLocalStorage() {
                    localStorage.setItem('cart', JSON.stringify(this.cart));
                },
                
                loadFromLocalStorage() {
                    const savedCart = localStorage.getItem('cart');
                    if (savedCart) {
                        this.cart = JSON.parse(savedCart);
                    }
                },
                
                showNotification(message, type = 'info') {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg text-white z-50 ${
                        type === 'success' ? 'bg-green-500' :
                        type === 'error' ? 'bg-red-500' :
                        'bg-blue-500'
                    }`;
                    notification.textContent = message;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                }
            }
        }
    </script>
</body>
</html>
