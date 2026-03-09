<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - ShopSmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&family=Roboto:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'DM Sans', 'Roboto', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
        }
        
        .team-card {
            transition: all 0.3s ease;
        }
        
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .timeline-item {
            position: relative;
            padding-left: 40px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #009245;
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            left: -5px;
            top: 8px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #009245;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #009245;
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
                        <a href="/products" class="text-gray-700 hover:text-green-600 font-medium transition">Products</a>
                        <a href="/services" class="text-gray-700 hover:text-green-600 font-medium transition">Services</a>
                        <a href="/about" class="text-green-600 font-medium transition border-b-2 border-green-600 pb-1">About</a>
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
                        About <span class="text-green-600">ShopSmart</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        We're on a mission to revolutionize online shopping in Tanzania by providing quality products, exceptional service, and unbeatable prices.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <div class="bg-white px-6 py-3 rounded-lg shadow-md">
                            <i class="fas fa-calendar text-green-600 mr-2"></i>
                            <span class="font-semibold">Founded 2020</span>
                        </div>
                        <div class="bg-white px-6 py-3 rounded-lg shadow-md">
                            <i class="fas fa-users text-green-600 mr-2"></i>
                            <span class="font-semibold">50K+ Customers</span>
                        </div>
                        <div class="bg-white px-6 py-3 rounded-lg shadow-md">
                            <i class="fas fa-globe text-green-600 mr-2"></i>
                            <span class="font-semibold">Nationwide Delivery</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="float-animation">
                        <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&h=400&fit=crop" alt="About Us" class="rounded-lg shadow-2xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Our Story</h2>
                    <p class="text-gray-600 mb-4">
                        ShopSmart was founded in 2020 with a simple vision: to make quality products accessible to everyone in Tanzania through the power of e-commerce.
                    </p>
                    <p class="text-gray-600 mb-4">
                        What started as a small operation with just a handful of products has grown into Tanzania's leading online shopping platform, serving thousands of customers across the country.
                    </p>
                    <p class="text-gray-600 mb-6">
                        Our journey has been driven by our commitment to customer satisfaction, quality assurance, and innovation in everything we do.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-bold text-green-600 mb-2">Our Mission</h3>
                            <p class="text-sm text-gray-600">To provide exceptional shopping experiences with quality products and outstanding service.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-bold text-green-600 mb-2">Our Vision</h3>
                            <p class="text-sm text-gray-600">To become Tanzania's most trusted and preferred online shopping destination.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72?w=600&h=400&fit=crop" alt="Our Story" class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Journey</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Key milestones in our growth story
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="space-y-8">
                    <div class="timeline-item">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="font-bold text-xl text-gray-900 mb-2">2020 - The Beginning</h3>
                            <p class="text-gray-600">ShopSmart was founded with a mission to transform online shopping in Tanzania. Started with just 100 products and a small team.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="font-bold text-xl text-gray-900 mb-2">2021 - Rapid Growth</h3>
                            <p class="text-gray-600">Expanded our product catalog to over 1,000 items and introduced mobile money payments for Tanzanian customers.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="font-bold text-xl text-gray-900 mb-2">2022 - Nationwide Expansion</h3>
                            <p class="text-gray-600">Launched nationwide delivery service and partnered with local businesses to reach every corner of Tanzania.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="font-bold text-xl text-gray-900 mb-2">2023 - Innovation</h3>
                            <p class="text-gray-600">Introduced our mobile app and AI-powered recommendation system, serving over 30,000 happy customers.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="font-bold text-xl text-gray-900 mb-2">2024 - Leading Platform</h3>
                            <p class="text-gray-600">Became Tanzania's leading e-commerce platform with 50,000+ customers and 10,000+ products.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Values</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    The principles that guide everything we do
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-3">Customer First</h3>
                    <p class="text-gray-600">We put our customers at the center of everything we do, ensuring their satisfaction and success.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-gem text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-3">Quality</h3>
                    <p class="text-gray-600">We never compromise on quality, ensuring every product meets our high standards.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lightbulb text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-3">Innovation</h3>
                    <p class="text-gray-600">We constantly innovate to improve our services and provide cutting-edge solutions.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-handshake text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-3">Integrity</h3>
                    <p class="text-gray-600">We operate with transparency, honesty, and ethical business practices in all our dealings.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Meet Our Team</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    The passionate people behind ShopSmart
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="team-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b332c1c3?w=300&h=300&fit=crop&crop=face" alt="CEO" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="font-bold text-xl text-gray-900 mb-1">David Ngungila</h3>
                        <p class="text-green-600 font-medium mb-3">Founder & CEO</p>
                        <p class="text-gray-600 text-sm mb-4">Visionary leader with over 10 years of experience in e-commerce and technology.</p>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="team-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=300&h=300&fit=crop&crop=face" alt="CTO" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="font-bold text-xl text-gray-900 mb-1">Sarah Johnson</h3>
                        <p class="text-green-600 font-medium mb-3">Chief Technology Officer</p>
                        <p class="text-gray-600 text-sm mb-4">Tech expert driving innovation and ensuring seamless platform performance.</p>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fab fa-github"></i></a>
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="team-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300&h=300&fit=crop&crop=face" alt="COO" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="font-bold text-xl text-gray-900 mb-1">Michael Chen</h3>
                        <p class="text-green-600 font-medium mb-3">Chief Operating Officer</p>
                        <p class="text-gray-600 text-sm mb-4">Operations specialist ensuring smooth day-to-day business operations.</p>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="team-card bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop&crop=face" alt="CMO" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="font-bold text-xl text-gray-900 mb-1">Amina Hassan</h3>
                        <p class="text-green-600 font-medium mb-3">Chief Marketing Officer</p>
                        <p class="text-gray-600 text-sm mb-4">Marketing genius building our brand and connecting with customers.</p>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-gray-400 hover:text-green-600"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="py-20 bg-gradient-to-r from-green-600 to-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">50K+</div>
                    <div class="text-green-100">Happy Customers</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">10K+</div>
                    <div class="text-green-100">Products</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">100+</div>
                    <div class="text-green-100">Team Members</div>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold mb-2">4.8</div>
                    <div class="text-green-100">Average Rating</div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function aboutPage() {
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
