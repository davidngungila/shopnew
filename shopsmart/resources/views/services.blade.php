<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - ShopSmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&family=Roboto:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'DM Sans', 'Roboto', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
        }
        
        .service-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #009245, #10b981);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .service-card:hover::before {
            transform: scaleX(1);
        }
        
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .service-icon-wrapper {
            background: linear-gradient(135deg, #009245 0%, #10b981 100%);
            transition: all 0.3s ease;
        }
        
        .service-card:hover .service-icon-wrapper {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 10px 20px rgba(0, 146, 69, 0.3);
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
    </style>
</head>
<body class="bg-gray-50">
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
                        <a href="/products" class="text-gray-700 hover:text-green-600 font-medium transition">Products</a>
                        <a href="/services" class="text-green-600 font-medium transition border-b-2 border-green-600 pb-1">Services</a>
                        <a href="/about" class="text-gray-700 hover:text-green-600 font-medium transition">About</a>
                        <a href="/contact" class="text-gray-700 hover:text-green-600 font-medium transition">Contact</a>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center space-x-4">
                        <a href="/login" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="gradient-bg hero-pattern py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        Our <span class="text-green-600">Premium Services</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        We offer comprehensive services designed to make your shopping experience exceptional, convenient, and secure.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#delivery" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-truck mr-2"></i>Fast Delivery
                        </a>
                        <a href="#support" class="border-2 border-green-600 text-green-600 px-6 py-3 rounded-lg hover:bg-green-50 transition">
                            <i class="fas fa-headset mr-2"></i>24/7 Support
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="float-animation">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop" alt="Services" class="rounded-lg shadow-2xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Services -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">What We Offer</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    From lightning-fast delivery to round-the-clock support, we've got you covered
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Fast Delivery -->
                <div id="delivery" class="service-card bg-white rounded-2xl p-8 shadow-lg">
                    <div class="service-icon-wrapper w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-truck text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Fast Delivery</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Get your orders delivered within 24 hours with our express delivery service. We partner with leading logistics companies to ensure your products reach you quickly and safely.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Same-day delivery available
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Real-time tracking
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Secure packaging
                        </li>
                    </ul>
                </div>

                <!-- Secure Payment -->
                <div class="service-card bg-white rounded-2xl p-8 shadow-lg">
                    <div class="service-icon-wrapper w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Secure Payment</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Your security is our priority. We use industry-leading encryption and fraud detection systems to protect your financial information and ensure safe transactions.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            SSL encryption
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Multiple payment options
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Fraud protection
                        </li>
                    </ul>
                </div>

                <!-- Easy Returns -->
                <div class="service-card bg-white rounded-2xl p-8 shadow-lg">
                    <div class="service-icon-wrapper w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-undo text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Easy Returns</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Not satisfied with your purchase? No problem! We offer a hassle-free 30-day return policy. Simply contact us and we'll handle the rest.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            30-day return policy
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Free return shipping
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Quick refunds
                        </li>
                    </ul>
                </div>

                <!-- 24/7 Support -->
                <div id="support" class="service-card bg-white rounded-2xl p-8 shadow-lg">
                    <div class="service-icon-wrapper w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-headset text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">24/7 Support</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Our dedicated customer support team is available round-the-clock to assist you with any questions, concerns, or issues you may have.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Live chat support
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Email support
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Phone support
                        </li>
                    </ul>
                </div>

                <!-- Price Match -->
                <div class="service-card bg-white rounded-2xl p-8 shadow-lg">
                    <div class="service-icon-wrapper w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-tag text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Price Match Guarantee</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Found the same product at a lower price elsewhere? We'll match it! Our price match guarantee ensures you always get the best deal.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Best price guarantee
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Easy claim process
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Quick verification
                        </li>
                    </ul>
                </div>

                <!-- Quality Assurance -->
                <div class="service-card bg-white rounded-2xl p-8 shadow-lg">
                    <div class="service-icon-wrapper w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-certificate text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Quality Assurance</h3>
                    <p class="text-gray-600 text-center mb-6">
                        All our products undergo rigorous quality checks to ensure they meet the highest standards. We only work with trusted suppliers and brands.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            100% genuine products
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Quality tested
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            Warranty included
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Additional Benefits</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Extra services that make your shopping experience even better
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 text-center shadow-md">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-gift text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Loyalty Rewards</h4>
                    <p class="text-gray-600 text-sm">Earn points with every purchase and redeem them for discounts</p>
                </div>

                <div class="bg-white rounded-xl p-6 text-center shadow-md">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Order Notifications</h4>
                    <p class="text-gray-600 text-sm">Get real-time updates about your order status</p>
                </div>

                <div class="bg-white rounded-xl p-6 text-center shadow-md">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Mobile App</h4>
                    <p class="text-gray-600 text-sm">Shop on the go with our mobile application</p>
                </div>

                <div class="bg-white rounded-xl p-6 text-center shadow-md">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Community</h4>
                    <p class="text-gray-600 text-sm">Join our community of satisfied customers</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-green-600 to-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Ready to Experience Our Services?
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Join thousands of satisfied customers who enjoy our premium services and exceptional shopping experience.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/products" class="bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Start Shopping
                </a>
                <a href="/contact" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-600 transition">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    <script>
        function servicesPage() {
            return {
                currentTime: '',
                
                init() {
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
                }
            }
        }
    </script>
</body>
</html>
