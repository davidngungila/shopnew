<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - ShopSmart</title>
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
    </style>
</head>
<body class="bg-gray-50" x-data="productsPage()">
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
                                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-shopping-bag text-white text-lg"></i>
                                </div>
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
                        <a href="/products" class="text-green-600 font-medium transition border-b-2 border-green-600 pb-1">Products</a>
                        <a href="/services" class="text-gray-700 hover:text-green-600 font-medium transition">Services</a>
                        <a href="/about" class="text-gray-700 hover:text-green-600 font-medium transition">About</a>
                        <a href="/contact" class="text-gray-700 hover:text-green-600 font-medium transition">Contact</a>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" x-model="searchQuery" @input="searchProducts()" placeholder="Search products..." 
                                   class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                        </div>
                        <button @click="toggleCart()" class="relative p-2 text-gray-700 hover:text-green-600">
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

    <!-- Hero Section -->
    <section class="gradient-bg hero-pattern py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Discover Amazing <span class="text-green-600">Products</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                    Browse our extensive collection of high-quality products at unbeatable prices
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="bg-white px-6 py-3 rounded-lg shadow-md">
                        <i class="fas fa-box text-green-600 mr-2"></i>
                        <span class="font-semibold">10,000+ Products</span>
                    </div>
                    <div class="bg-white px-6 py-3 rounded-lg shadow-md">
                        <i class="fas fa-truck text-green-600 mr-2"></i>
                        <span class="font-semibold">Fast Delivery</span>
                    </div>
                    <div class="bg-white px-6 py-3 rounded-lg shadow-md">
                        <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                        <span class="font-semibold">Secure Payment</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters and Categories -->
    <section class="py-8 bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Categories -->
                <div class="flex flex-wrap gap-2">
                    <button @click="selectedCategory = null" 
                            :class="selectedCategory === null ? 'active' : 'bg-gray-100 text-gray-700'"
                            class="category-pill px-4 py-2 rounded-full font-medium transition">
                        All Products
                    </button>
                    <template x-for="category in categories" :key="category.id">
                        <button @click="selectedCategory = category.id" 
                                :class="selectedCategory === category.id ? 'active' : 'bg-gray-100 text-gray-700'"
                                class="category-pill px-4 py-2 rounded-full font-medium transition">
                            <i :class="category.icon" class="mr-1"></i>
                            <span x-text="category.name"></span>
                        </button>
                    </template>
                </div>

                <!-- Sort and View Options -->
                <div class="flex items-center gap-4">
                    <select x-model="sortBy" @change="sortProducts()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="name">Sort by Name</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="rating">Highest Rated</option>
                        <option value="newest">Newest First</option>
                    </select>
                    <div class="flex space-x-2">
                        <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-3 py-1 rounded">
                            <i class="fas fa-th"></i>
                        </button>
                        <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-3 py-1 rounded">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Results Count -->
            <div class="mb-6 flex justify-between items-center">
                <p class="text-gray-600">
                    Showing <span x-text="filteredProducts.length"></span> products
                </p>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Price Range:</span>
                    <input type="number" x-model="priceRange.min" placeholder="Min" class="w-20 px-2 py-1 border rounded">
                    <span class="text-gray-500">-</span>
                    <input type="number" x-model="priceRange.max" placeholder="Max" class="w-20 px-2 py-1 border rounded">
                    <button @click="applyPriceFilter()" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                        Apply
                    </button>
                </div>
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
                                    <span class="text-2xl font-bold text-green-600">TZS <span x-text="product.price"></span></span>
                                    <span x-show="product.originalPrice" class="text-sm text-gray-400 line-through ml-2">TZS <span x-text="product.originalPrice"></span></span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-box"></i> <span x-text="product.stock"></span> left
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="addToCart(product)" class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                                </button>
                                <button @click="buyNow(product)" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                    <i class="fas fa-bolt mr-2"></i>Buy Now
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Load More -->
            <div class="text-center mt-8">
                <button @click="loadMoreProducts()" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition">
                    Load More Products
                </button>
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
                        <span class="text-2xl font-bold text-green-600">TZS <span x-text="cartTotal"></span></span>
                    </div>
                    <button @click="proceedToCheckout()" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function productsPage() {
            return {
                products: [],
                categories: [],
                cart: [],
                searchQuery: '',
                sortBy: 'name',
                viewMode: 'grid',
                selectedCategory: null,
                showCart: false,
                currentTime: '',
                priceRange: { min: '', max: '' },
                
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
                    
                    // Apply price filter
                    if (this.priceRange.min) {
                        filtered = filtered.filter(product => product.price >= this.priceRange.min);
                    }
                    if (this.priceRange.max) {
                        filtered = filtered.filter(product => product.price <= this.priceRange.max);
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
                            case 'newest':
                                return new Date(b.createdAt) - new Date(a.createdAt);
                            default:
                                return 0;
                        }
                    });
                    
                    return filtered;
                },
                
                get cartTotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },
                
                init() {
                    this.loadProducts();
                    this.loadCategories();
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
                            id: 1,
                            name: 'Wireless Bluetooth Earbuds',
                            description: 'Premium sound quality with noise cancellation',
                            price: 45000,
                            originalPrice: 65000,
                            discount: 31,
                            rating: 4.5,
                            stock: 15,
                            categoryId: 1,
                            image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=200&fit=crop',
                            createdAt: '2024-01-15'
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
                            image: 'https://images.unsplash.com/photo-1523275335684-e346ac5d7ff1?w=300&h=200&fit=crop',
                            createdAt: '2024-01-20'
                        },
                        {
                            id: 3,
                            name: 'Organic Coffee Beans',
                            description: 'Premium arabica coffee from local farms',
                            price: 25000,
                            rating: 4.8,
                            stock: 50,
                            categoryId: 2,
                            image: 'https://images.unsplash.com/photo-1495474472287-4d2b2c0b723b?w=300&h=200&fit=crop',
                            createdAt: '2024-01-10'
                        }
                    ];
                },
                
                loadCategories() {
                    this.categories = [
                        { id: 1, name: 'Electronics', icon: 'fas fa-laptop' },
                        { id: 2, name: 'Food & Beverages', icon: 'fas fa-coffee' },
                        { id: 3, name: 'Sports & Fitness', icon: 'fas fa-dumbbell' },
                        { id: 4, name: 'Home & Living', icon: 'fas fa-home' },
                        { id: 5, name: 'Fashion', icon: 'fas fa-tshirt' }
                    ];
                },
                
                searchProducts() {
                    // Search functionality handled by computed property
                },
                
                sortProducts() {
                    // Sort functionality handled by computed property
                },
                
                applyPriceFilter() {
                    // Price filter handled by computed property
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
                
                toggleCart() {
                    this.showCart = !this.showCart;
                },
                
                proceedToCheckout() {
                    // Implement checkout logic
                    this.showCart = false;
                    this.showNotification('Proceeding to checkout...', 'info');
                },
                
                loadMoreProducts() {
                    // Implement load more functionality
                    this.showNotification('Loading more products...', 'info');
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
