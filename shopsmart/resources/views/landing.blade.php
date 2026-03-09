<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopSmart - Professional Online Shopping Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .product-card {
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .category-pill {
            transition: all 0.2s ease;
        }
        .category-pill:hover {
            transform: scale(1.05);
        }
        .testimonial-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .feature-icon {
            transition: all 0.3s ease;
        }
        .feature-icon:hover {
            transform: rotate(360deg) scale(1.1);
        }
    </style>
</head>
<body class="bg-gray-50" x-data="landingPage()">
    <!-- Professional Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                            <i class="fas fa-shopping-bag mr-2"></i>ShopSmart
                        </h1>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#home" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium transition">Home</a>
                        <a href="#products" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium transition">Products</a>
                        <a href="#categories" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium transition">Categories</a>
                        <a href="#deals" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium transition">Deals</a>
                        <a href="#testimonials" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium transition">Reviews</a>
                        <a href="#contact" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium transition">Contact</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" x-model="searchQuery" @input="searchProducts()" placeholder="Search products..." 
                               class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                    </div>
                    <button @click="toggleCart()" class="relative p-2 text-gray-700 hover:text-purple-600">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span x-show="cart.length > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" x-text="cart.length"></span>
                    </button>
                    <button @click="showUserMenu = !showUserMenu" class="flex items-center space-x-2 text-gray-700 hover:text-purple-600">
                        <i class="fas fa-user-circle text-xl"></i>
                        <span class="hidden md:block">Account</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="gradient-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl font-bold mb-6 float-animation">
                        Shop Smart, Live Better
                    </h1>
                    <p class="text-xl mb-8 text-purple-100">
                        Discover amazing products at unbeatable prices. Fast delivery, secure payments, and 24/7 customer support.
                    </p>
                    <div class="flex flex-wrap gap-4 mb-8">
                        <button @click="scrollToProducts()" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-purple-50 transition transform hover:scale-105">
                            <i class="fas fa-shopping-bag mr-2"></i>Shop Now
                        </button>
                        <button @click="playVideo()" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition transform hover:scale-105">
                            <i class="fas fa-play-circle mr-2"></i>Watch Demo
                        </button>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold">50K+</div>
                            <div class="text-sm text-purple-100">Happy Customers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">10K+</div>
                            <div class="text-sm text-purple-100">Products</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">24/7</div>
                            <div class="text-sm text-purple-100">Support</div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="float-animation">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop" alt="Shopping" class="rounded-lg shadow-2xl">
                    </div>
                    <div class="absolute -bottom-4 -left-4 bg-white rounded-lg shadow-lg p-4">
                        <div class="flex items-center space-x-2">
                            <div class="bg-green-500 rounded-full p-2">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <div class="font-semibold">Free Delivery</div>
                                <div class="text-sm text-gray-600">On orders over TZS 50,000</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose ShopSmart?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="feature-icon w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-truck text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Fast Delivery</h3>
                    <p class="text-gray-600 text-sm">Get your orders delivered within 24 hours</p>
                </div>
                <div class="text-center">
                    <div class="feature-icon w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Secure Payment</h3>
                    <p class="text-gray-600 text-sm">100% secure payment processing</p>
                </div>
                <div class="text-center">
                    <div class="feature-icon w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-undo text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Easy Returns</h3>
                    <p class="text-gray-600 text-sm">30-day return policy</p>
                </div>
                <div class="text-center">
                    <div class="feature-icon w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">24/7 Support</h3>
                    <p class="text-gray-600 text-sm">Round-the-clock customer service</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Shop by Category</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <template x-for="category in categories" :key="category.id">
                    <div @click="filterByCategory(category.id)" class="category-pill bg-white rounded-lg p-4 text-center cursor-pointer hover:shadow-lg">
                        <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center" :class="category.color">
                            <i :class="category.icon" class="text-white text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-sm" x-text="category.name"></h3>
                        <p class="text-xs text-gray-500" x-text="`${category.count} products`"></p>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Featured Products</h2>
                <div class="flex items-center space-x-4">
                    <select x-model="sortBy" @change="sortProducts()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="name">Sort by Name</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="rating">Highest Rated</option>
                    </select>
                    <div class="flex space-x-2">
                        <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-3 py-1 rounded">
                            <i class="fas fa-th"></i>
                        </button>
                        <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-3 py-1 rounded">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search Results -->
            <div x-show="searchQuery" class="mb-4 p-3 bg-purple-50 rounded-lg">
                <p class="text-purple-700">
                    <i class="fas fa-search mr-2"></i>
                    Searching for "<span x-text="searchQuery"></span>" - <span x-text="filteredProducts.length"></span> results found
                </p>
            </div>

            <!-- Products Grid/List -->
            <div :class="viewMode === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6' : 'space-y-4'">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="relative">
                            <img :src="product.image" :alt="product.name" class="w-full h-48 object-cover">
                            <div x-show="product.discount" class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
                                -<span x-text="product.discount"></span>%
                            </div>
                            <div class="absolute bottom-2 left-2 bg-white px-2 py-1 rounded text-xs">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span x-text="product.rating"></span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2" x-text="product.name"></h3>
                            <p class="text-gray-600 text-sm mb-3" x-text="product.description"></p>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <span class="text-2xl font-bold text-purple-600">TZS <span x-text="product.price"></span></span>
                                    <span x-show="product.originalPrice" class="text-sm text-gray-400 line-through ml-2">TZS <span x-text="product.originalPrice"></span></span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-box"></i> <span x-text="product.stock"></span> left
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="addToCart(product)" class="flex-1 bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
                                    <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                                </button>
                                <button @click="buyNow(product)" class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-bolt mr-2"></i>Buy Now
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Load More -->
            <div class="text-center mt-8">
                <button @click="loadMoreProducts()" class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 transition">
                    Load More Products
                </button>
            </div>
        </div>
    </section>

    <!-- Special Deals Section -->
    <section id="deals" class="py-16 bg-gradient-to-r from-purple-600 to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">🔥 Hot Deals - Limited Time Offers!</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="deal in hotDeals" :key="deal.id">
                    <div class="bg-white rounded-lg p-6 text-gray-900">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-lg" x-text="deal.name"></h3>
                                <p class="text-gray-600 text-sm" x-text="deal.description"></p>
                            </div>
                            <div class="bg-red-500 text-white px-2 py-1 rounded text-sm">
                                <span x-text="deal.discount"></span>% OFF
                            </div>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-2xl font-bold text-purple-600">TZS <span x-text="deal.price"></span></span>
                                <span class="text-sm text-gray-400 line-through ml-2">TZS <span x-text="deal.originalPrice"></span></span>
                            </div>
                            <div class="text-sm text-red-500">
                                <i class="fas fa-clock"></i> <span x-text="deal.timeLeft"></span>
                            </div>
                        </div>
                        <button @click="buyNow(deal)" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-2 rounded-lg hover:opacity-90 transition">
                            Get Deal Now
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="testimonial in testimonials" :key="testimonial.id">
                    <div class="testimonial-card rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <img :src="testimonial.avatar" :alt="testimonial.name" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h4 class="font-semibold" x-text="testimonial.name"></h4>
                                <div class="flex text-yellow-400">
                                    <template x-for="i in 5" :key="i">
                                        <i class="fas fa-star"></i>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-700 italic" x-text="testimonial.comment"></p>
                        <div class="mt-4 text-sm text-gray-500">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            Verified Purchase
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Stay Updated with Latest Deals</h2>
            <p class="text-purple-100 mb-8">Subscribe to our newsletter and never miss an offer!</p>
            <form @submit.prevent="subscribeNewsletter()" class="max-w-md mx-auto flex">
                <input type="email" x-model="newsletterEmail" placeholder="Enter your email" required
                       class="flex-1 px-4 py-3 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-purple-300 text-gray-900">
                <button type="submit" class="bg-white text-purple-600 px-6 py-3 rounded-r-lg hover:bg-purple-50 transition font-semibold">
                    Subscribe
                </button>
            </form>
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
                                    <p class="text-gray-600">TZS <span x-text="item.price"></span></p>
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
                        <span class="text-2xl font-bold text-purple-600">TZS <span x-text="cartTotal"></span></span>
                    </div>
                    <button @click="proceedToCheckout()" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div x-show="showCheckout" @click.away="showCheckout = false" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click.stop class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Secure Checkout</h3>
                    <button @click="showCheckout = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Order Summary -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold mb-2">Order Summary</h4>
                    <div class="space-y-2">
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex justify-between text-sm">
                                <span><span x-text="item.name"></span> x <span x-text="item.quantity"></span></span>
                                <span>TZS <span x-text="item.price * item.quantity"></span></span>
                            </div>
                        </template>
                    </div>
                    <div class="border-t mt-2 pt-2">
                        <div class="flex justify-between font-bold">
                            <span>Total:</span>
                            <span class="text-purple-600">TZS <span x-text="cartTotal"></span></span>
                        </div>
                    </div>
                </div>
                
                <!-- Customer Information -->
                <form @submit.prevent="processPayment()" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" x-model="customerInfo.firstName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" x-model="customerInfo.lastName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" x-model="customerInfo.email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                        <input type="tel" x-model="customerInfo.phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Address *</label>
                        <textarea x-model="customerInfo.address" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>
                    
                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" x-model="paymentMethod" value="card" required class="mr-2">
                                <span>Credit/Debit Card</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" x-model="paymentMethod" value="mobile" required class="mr-2">
                                <span>Mobile Money (M-Pesa, Tigo Pesa)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" x-model="paymentMethod" value="bank" required class="mr-2">
                                <span>Bank Transfer</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Card Payment Form -->
                    <div x-show="paymentMethod === 'card'" class="space-y-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Number *</label>
                            <input type="text" x-model="cardInfo.number" placeholder="1234 5678 9012 3456" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date *</label>
                                <input type="text" x-model="cardInfo.expiry" placeholder="MM/YY" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CVV *</label>
                                <input type="text" x-model="cardInfo.cvv" placeholder="123" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Money Form -->
                    <div x-show="paymentMethod === 'mobile'" class="space-y-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number *</label>
                            <input type="tel" x-model="mobileInfo.number" placeholder="+255 7XX XXX XXX" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provider *</label>
                            <select x-model="mobileInfo.provider" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Select Provider</option>
                                <option value="mpesa">M-Pesa</option>
                                <option value="tigo">Tigo Pesa</option>
                                <option value="airtel">Airtel Money</option>
                                <option value="halopesa">Halopesa</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" :disabled="processing" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition disabled:opacity-50">
                        <span x-show="!processing">
                            <i class="fas fa-lock mr-2"></i>Complete Payment - TZS <span x-text="cartTotal"></span>
                        </span>
                        <span x-show="processing">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-show="showSuccess" @click.away="showSuccess = false" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click.stop class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Payment Successful!</h3>
                <p class="text-gray-600 mb-4">Your order has been placed successfully. Order ID: #<span x-text="orderId"></span></p>
                <p class="text-gray-600 mb-6">You will receive a confirmation email shortly.</p>
                <button @click="showSuccess = false" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
                    Continue Shopping
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">ShopSmart</h3>
                    <p class="text-gray-400">Your trusted online shopping partner for quality products and great deals.</p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">About Us</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-white">FAQs</a></li>
                        <li><a href="#" class="hover:text-white">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Customer Service</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Shipping Info</a></li>
                        <li><a href="#" class="hover:text-white">Returns</a></li>
                        <li><a href="#" class="hover:text-white">Size Guide</a></li>
                        <li><a href="#" class="hover:text-white">Track Order</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone mr-2"></i>+255 712 345 678</li>
                        <li><i class="fas fa-envelope mr-2"></i>info@shopsmart.co.tz</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Dar es Salaam, Tanzania</li>
                        <li><i class="fas fa-clock mr-2"></i>Mon-Sat: 9AM-8PM</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 ShopSmart. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function landingPage() {
            return {
                // Data
                products: [],
                categories: [],
                cart: [],
                searchQuery: '',
                sortBy: 'name',
                viewMode: 'grid',
                selectedCategory: null,
                showCart: false,
                showCheckout: false,
                showSuccess: false,
                processing: false,
                orderId: '',
                paymentMethod: 'card',
                newsletterEmail: '',
                
                // Customer Information
                customerInfo: {
                    firstName: '',
                    lastName: '',
                    email: '',
                    phone: '',
                    address: ''
                },
                
                // Card Information
                cardInfo: {
                    number: '',
                    expiry: '',
                    cvv: ''
                },
                
                // Mobile Money Information
                mobileInfo: {
                    number: '',
                    provider: ''
                },
                
                // Hot Deals
                hotDeals: [
                    {
                        id: 1,
                        name: 'Smartphone Pro Max',
                        description: 'Latest flagship smartphone with amazing features',
                        price: 899000,
                        originalPrice: 1299000,
                        discount: 31,
                        timeLeft: '2h 34m',
                        image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&h=200&fit=crop'
                    },
                    {
                        id: 2,
                        name: 'Laptop Ultra Slim',
                        description: 'Powerful laptop for work and gaming',
                        price: 1450000,
                        originalPrice: 1899000,
                        discount: 24,
                        timeLeft: '5h 12m',
                        image: 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300&h=200&fit=crop'
                    },
                    {
                        id: 3,
                        name: 'Wireless Headphones',
                        description: 'Premium noise-cancelling headphones',
                        price: 189000,
                        originalPrice: 299000,
                        discount: 37,
                        timeLeft: '1d 3h',
                        image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=200&fit=crop'
                    }
                ],
                
                // Testimonials
                testimonials: [
                    {
                        id: 1,
                        name: 'Sarah Johnson',
                        avatar: 'https://images.unsplash.com/photo-1494790108755-2616b332c1c3?w=100&h=100&fit=crop&crop=face',
                        comment: 'Amazing shopping experience! Fast delivery and great customer service. Will definitely shop again!'
                    },
                    {
                        id: 2,
                        name: 'Michael Chen',
                        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face',
                        comment: 'Great products at competitive prices. The website is easy to use and checkout process is smooth.'
                    },
                    {
                        id: 3,
                        name: 'Amina Hassan',
                        avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&h=100&fit=crop&crop=face',
                        comment: 'Love the variety of products available. Customer support is very helpful and responsive.'
                    }
                ],
                
                // Computed
                get filteredProducts() {
                    let filtered = this.products;
                    
                    if (this.searchQuery) {
                        filtered = filtered.filter(product => 
                            product.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            product.description.toLowerCase().includes(this.searchQuery.toLowerCase())
                        );
                    }
                    
                    if (this.selectedCategory) {
                        filtered = filtered.filter(product => product.categoryId === this.selectedCategory);
                    }
                    
                    // Sort products
                    filtered.sort((a, b) => {
                        switch(this.sortBy) {
                            case 'name':
                                return a.name.localeCompare(b.name);
                            case 'price-low':
                                return a.price - b.price;
                            case 'price-high':
                                return b.price - a.price;
                            case 'rating':
                                return b.rating - a.rating;
                            default:
                                return 0;
                        }
                    });
                    
                    return filtered;
                },
                
                get cartTotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },
                
                // Methods
                init() {
                    this.loadProducts();
                    this.loadCategories();
                    this.loadFromLocalStorage();
                },
                
                loadProducts() {
                    // Sample products - in real app, this would come from API
                    this.products = [
                        {
                            id: 1,
                            name: 'Wireless Bluetooth Earbuds',
                            description: 'Premium sound quality with noise cancellation',
                            price: 45000,
                            originalPrice: 65000,
                            discount: 31,
                            rating: 4.5,
                            stock: 15,
                            categoryId: 1,
                            image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=200&fit=crop'
                        },
                        {
                            id: 2,
                            name: 'Smart Watch Pro',
                            description: 'Track your fitness and stay connected',
                            price: 125000,
                            originalPrice: 189000,
                            discount: 34,
                            rating: 4.7,
                            stock: 8,
                            categoryId: 1,
                            image: 'https://images.unsplash.com/photo-1523275335684-e346ac5d7ff1?w=300&h=200&fit=crop'
                        },
                        {
                            id: 3,
                            name: 'Organic Coffee Beans',
                            description: 'Premium arabica coffee from local farms',
                            price: 25000,
                            rating: 4.8,
                            stock: 50,
                            categoryId: 2,
                            image: 'https://images.unsplash.com/photo-1495474472287-4d2b2c0b723b?w=300&h=200&fit=crop'
                        },
                        {
                            id: 4,
                            name: 'Yoga Mat Premium',
                            description: 'Non-slip exercise mat with carrying strap',
                            price: 35000,
                            rating: 4.3,
                            stock: 25,
                            categoryId: 3,
                            image: 'https://images.unsplash.com/photo-1548619665-ba11c946c6eb?w=300&h=200&fit=crop'
                        },
                        {
                            id: 5,
                            name: 'LED Desk Lamp',
                            description: 'Adjustable brightness with USB charging port',
                            price: 28000,
                            originalPrice: 45000,
                            discount: 38,
                            rating: 4.6,
                            stock: 12,
                            categoryId: 4,
                            image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=200&fit=crop'
                        },
                        {
                            id: 6,
                            name: 'Stainless Steel Water Bottle',
                            description: 'Insulated bottle keeps drinks cold for 24 hours',
                            price: 18000,
                            rating: 4.4,
                            stock: 100,
                            categoryId: 5,
                            image: 'https://images.unsplash.com/photo-1523049673857-eb18f1d7b578?w=300&h=200&fit=crop'
                        }
                    ];
                },
                
                loadCategories() {
                    this.categories = [
                        { id: 1, name: 'Electronics', icon: 'fas fa-laptop', color: 'bg-blue-500', count: 245 },
                        { id: 2, name: 'Food & Beverages', icon: 'fas fa-coffee', color: 'bg-green-500', count: 189 },
                        { id: 3, name: 'Sports & Fitness', icon: 'fas fa-dumbbell', color: 'bg-orange-500', count: 156 },
                        { id: 4, name: 'Home & Living', icon: 'fas fa-home', color: 'bg-purple-500', count: 234 },
                        { id: 5, name: 'Accessories', icon: 'fas fa-glasses', color: 'bg-pink-500', count: 312 },
                        { id: 6, name: 'Fashion', icon: 'fas fa-tshirt', color: 'bg-red-500', count: 428 }
                    ];
                },
                
                searchProducts() {
                    // Search functionality is handled by computed property
                },
                
                sortProducts() {
                    // Sort functionality is handled by computed property
                },
                
                filterByCategory(categoryId) {
                    this.selectedCategory = this.selectedCategory === categoryId ? null : categoryId;
                    this.scrollToProducts();
                },
                
                scrollToProducts() {
                    document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
                },
                
                toggleCart() {
                    this.showCart = !this.showCart;
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
                
                buyNow(product) {
                    this.addToCart(product);
                    this.showCart = true;
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
                
                removeFromCart(productId) {
                    this.cart = this.cart.filter(item => item.id !== productId);
                    this.saveToLocalStorage();
                },
                
                proceedToCheckout() {
                    this.showCart = false;
                    this.showCheckout = true;
                },
                
                async processPayment() {
                    this.processing = true;
                    
                    // Simulate payment processing
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    
                    // Generate order ID
                    this.orderId = 'ORD' + Date.now();
                    
                    // Clear cart
                    this.cart = [];
                    this.saveToLocalStorage();
                    
                    // Show success modal
                    this.showCheckout = false;
                    this.showSuccess = true;
                    this.processing = false;
                    
                    // Reset form
                    this.customerInfo = {
                        firstName: '',
                        lastName: '',
                        email: '',
                        phone: '',
                        address: ''
                    };
                    this.cardInfo = { number: '', expiry: '', cvv: '' };
                    this.mobileInfo = { number: '', provider: '' };
                },
                
                subscribeNewsletter() {
                    if (this.newsletterEmail) {
                        this.showNotification('Successfully subscribed to newsletter!', 'success');
                        this.newsletterEmail = '';
                    }
                },
                
                playVideo() {
                    // Implement video playback
                    this.showNotification('Video coming soon!', 'info');
                },
                
                loadMoreProducts() {
                    // Implement load more functionality
                    this.showNotification('More products coming soon!', 'info');
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
