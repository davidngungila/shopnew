<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - ShopSmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&family=Roboto:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'DM Sans', 'Roboto', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
        }
        
        .contact-card {
            transition: all 0.3s ease;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
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
        
        .contact-icon-wrapper {
            background: linear-gradient(135deg, #009245 0%, #10b981 100%);
            transition: all 0.3s ease;
        }
        
        .contact-card:hover .contact-icon-wrapper {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 10px 20px rgba(0, 146, 69, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50" x-data="contactPage()">
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
                        <a href="/services" class="text-gray-700 hover:text-green-600 font-medium transition">Services</a>
                        <a href="/about" class="text-gray-700 hover:text-green-600 font-medium transition">About</a>
                        <a href="/contact" class="text-green-600 font-medium transition border-b-2 border-green-600 pb-1">Contact</a>
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
                        Get in <span class="text-green-600">Touch</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        We're here to help! Whether you have questions about our products, need assistance with your order, or want to learn more about our services, our team is ready to assist you.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <div class="bg-white px-6 py-3 rounded-lg shadow-md">
                            <i class="fas fa-phone text-green-600 mr-2"></i>
                            <span class="font-semibold">24/7 Support</span>
                        </div>
                        <div class="bg-white px-6 py-3 rounded-lg shadow-md">
                            <i class="fas fa-clock text-green-600 mr-2"></i>
                            <span class="font-semibold">Quick Response</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="float-animation">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop" alt="Contact Us" class="rounded-lg shadow-2xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Options -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How to Reach Us</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Choose the most convenient way to get in touch with our team
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Phone -->
                <div class="contact-card bg-white rounded-2xl p-8 shadow-lg text-center">
                    <div class="contact-icon-wrapper w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-phone text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Phone Support</h3>
                    <p class="text-gray-600 mb-6">
                        Speak directly with our customer support team for immediate assistance.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-center text-gray-700">
                            <i class="fas fa-phone-alt mr-2 text-green-600"></i>
                            <span>+255 712 345 678</span>
                        </div>
                        <div class="flex items-center justify-center text-gray-700">
                            <i class="fas fa-phone-alt mr-2 text-green-600"></i>
                            <span>+255 754 678 912</span>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500">
                        Available 24/7
                    </div>
                </div>

                <!-- Email -->
                <div class="contact-card bg-white rounded-2xl p-8 shadow-lg text-center">
                    <div class="contact-icon-wrapper w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-envelope text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Email Support</h3>
                    <p class="text-gray-600 mb-6">
                        Send us an email and we'll respond within 24 hours with detailed assistance.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-center text-gray-700">
                            <i class="fas fa-envelope mr-2 text-green-600"></i>
                            <span>info@shopsmart.co.tz</span>
                        </div>
                        <div class="flex items-center justify-center text-gray-700">
                            <i class="fas fa-envelope mr-2 text-green-600"></i>
                            <span>support@shopsmart.co.tz</span>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500">
                        Response within 24 hours
                    </div>
                </div>

                <!-- Live Chat -->
                <div class="contact-card bg-white rounded-2xl p-8 shadow-lg text-center">
                    <div class="contact-icon-wrapper w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-comments text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Live Chat</h3>
                    <p class="text-gray-600 mb-6">
                        Get instant help through our live chat feature available on our website.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-center text-gray-700">
                            <i class="fas fa-globe mr-2 text-green-600"></i>
                            <span>www.shopsmart.co.tz</span>
                        </div>
                        <div class="flex items-center justify-center text-gray-700">
                            <i class="fas fa-mobile-alt mr-2 text-green-600"></i>
                            <span>Mobile App Available</span>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500">
                        Available 24/7
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Form -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Send Us a Message</h2>
                    <form @submit.prevent="submitForm()" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                <input type="text" x-model="form.firstName" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                <input type="text" x-model="form.lastName" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" x-model="form.email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" x-model="form.phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                            <select x-model="form.subject" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Select a subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="support">Customer Support</option>
                                <option value="order">Order Related</option>
                                <option value="product">Product Information</option>
                                <option value="feedback">Feedback</option>
                                <option value="partnership">Partnership</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                            <textarea x-model="form.message" required rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" x-model="form.newsletter" class="mr-2">
                            <label class="text-sm text-gray-600">I'd like to receive newsletters and updates</label>
                        </div>

                        <button type="submit" :disabled="submitting" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition disabled:opacity-50">
                            <span x-show="!submitting">
                                <i class="fas fa-paper-plane mr-2"></i>Send Message
                            </span>
                            <span x-show="submitting">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Sending...
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Info -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Visit Our Office</h2>
                    <div class="bg-white rounded-2xl p-8 shadow-lg mb-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-green-600 text-xl mr-4 mt-1"></i>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Address</h3>
                                    <p class="text-gray-600">
                                        ShopSmart Headquarters<br>
                                        Uhuru Street, Kivukoni<br>
                                        Dar es Salaam, Tanzania<br>
                                        P.O. Box: 12345, Dar es Salaam
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-clock text-green-600 text-xl mr-4 mt-1"></i>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Business Hours</h3>
                                    <p class="text-gray-600">
                                        Monday - Friday: 9:00 AM - 8:00 PM<br>
                                        Saturday: 9:00 AM - 6:00 PM<br>
                                        Sunday: 10:00 AM - 4:00 PM<br>
                                        Public Holidays: 10:00 AM - 2:00 PM
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-shipping-fast text-green-600 text-xl mr-4 mt-1"></i>
                                <div>
                                    <h3 class="font-bold text-gray-900 mb-1">Delivery Information</h3>
                                    <p class="text-gray-600">
                                        We deliver nationwide across Tanzania. Same-day delivery available in Dar es Salaam, 2-3 days for other regions.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="bg-white rounded-2xl p-4 shadow-lg">
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-map-marked-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500">Interactive Map</p>
                                <p class="text-sm text-gray-400">Find us easily on Google Maps</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Quick answers to common questions
                </p>
            </div>

            <div class="max-w-3xl mx-auto">
                <div class="space-y-4">
                    <template x-for="(faq, index) in faqs" :key="index">
                        <div class="bg-gray-50 rounded-lg overflow-hidden">
                            <button @click="faq.open = !faq.open" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-100 transition">
                                <span class="font-semibold text-gray-900" x-text="faq.question"></span>
                                <i class="fas fa-chevron-down text-gray-500 transition-transform" :class="faq.open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="faq.open" x-transition class="px-6 py-4 border-t border-gray-200">
                                <p class="text-gray-600" x-text="faq.answer"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Modal -->
    <div x-show="showSuccess" @click.away="showSuccess = false" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click.stop class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Message Sent!</h3>
                <p class="text-gray-600 mb-4">Thank you for contacting us. We'll get back to you within 24 hours.</p>
                <button @click="showSuccess = false" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function contactPage() {
            return {
                currentTime: '',
                submitting: false,
                showSuccess: false,
                form: {
                    firstName: '',
                    lastName: '',
                    email: '',
                    phone: '',
                    subject: '',
                    message: '',
                    newsletter: false
                },
                faqs: [
                    {
                        question: 'How long does delivery take?',
                        answer: 'Delivery times vary by location. Dar es Salaam: Same-day or next-day. Other major cities: 2-3 days. Rural areas: 3-5 days.',
                        open: false
                    },
                    {
                        question: 'What payment methods do you accept?',
                        answer: 'We accept M-Pesa, Tigo Pesa, Airtel Money, Halopesa, bank transfers, and credit/debit cards.',
                        open: false
                    },
                    {
                        question: 'Can I return or exchange products?',
                        answer: 'Yes! We offer a 30-day return policy for most products. Items must be unused and in original packaging.',
                        open: false
                    },
                    {
                        question: 'How do I track my order?',
                        answer: 'Once your order is shipped, you\'ll receive a tracking number via email/SMS. You can track on our website or mobile app.',
                        open: false
                    },
                    {
                        question: 'Do you offer discounts for bulk orders?',
                        answer: 'Yes, we offer special pricing for bulk orders. Please contact our sales team for a custom quote.',
                        open: false
                    }
                ],
                
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
                },
                
                async submitForm() {
                    this.submitting = true;
                    
                    // Simulate form submission
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    
                    // Reset form
                    this.form = {
                        firstName: '',
                        lastName: '',
                        email: '',
                        phone: '',
                        subject: '',
                        message: '',
                        newsletter: false
                    };
                    
                    this.submitting = false;
                    this.showSuccess = true;
                }
            }
        }
    </script>
</body>
</html>
