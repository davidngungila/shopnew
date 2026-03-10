@extends('layouts.app')

@section('title', 'SMS Configuration')

@section('content')
<div class="space-y-6" x-data="smsConfig()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">SMS Configuration</h1>
            <p class="text-gray-600 mt-1">Configure SMS settings and integrate with Messaging Service API V2</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('settings.communication') }}" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors" style="background-color: #6b7280;">
                <i class="fas fa-arrow-left mr-2"></i>Back to Communication
            </a>
            <button @click="testConfiguration()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-vial mr-2"></i>Test Configuration
            </button>
        </div>
    </div>

    <!-- Status Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Advanced Configuration Options -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-sliders-h mr-2 text-indigo-500"></i>
            Advanced Configuration Options
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Message Templates -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-file-alt mr-1 text-blue-500"></i>Message Templates
                </label>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <input type="checkbox" name="enable_templates" value="1" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   x-model="config.enable_templates">
                            <label for="enable_templates" class="ml-2 text-sm font-medium text-gray-700">
                                Enable Message Templates
                            </label>
                        </div>
                        <button @click="showTemplates()" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-list mr-1"></i>Manage Templates
                        </button>
                    </div>
                    
                    <div x-show="config.enable_templates" class="mt-3">
                        <select name="default_template" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                x-model="config.default_template">
                            <option value="">Select default template</option>
                            <option value="welcome">Welcome Message</option>
                            <option value="verification">Verification Code</option>
                            <option value="appointment">Appointment Reminder</option>
                            <option value="payment">Payment Confirmation</option>
                            <option value="promotion">Promotional Message</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Scheduling Options -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-clock mr-1 text-green-500"></i>Scheduling Options
                </label>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <input type="checkbox" name="enable_scheduling" value="1" 
                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                   x-model="config.enable_scheduling">
                            <label for="enable_scheduling" class="ml-2 text-sm font-medium text-gray-700">
                                Enable Message Scheduling
                            </label>
                        </div>
                        <button @click="showSchedulePreview()" class="text-green-600 hover:text-green-800 text-sm">
                            <i class="fas fa-calendar mr-1"></i>Preview Schedule
                        </button>
                    </div>
                    
                    <div x-show="config.enable_scheduling" class="mt-3 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Default Send Time</label>
                            <input type="time" name="default_send_time" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   value="09:00" 
                                   x-model="config.default_send_time">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Time Zone</label>
                            <select name="timezone" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    x-model="config.timezone">
                                <option value="UTC">UTC</option>
                                <option value="Africa/Nairobi">Africa/Nairobi (EAT)</option>
                                <option value="Africa/Dar_es_Salaam">Africa/Dar es Salaam (EAT)</option>
                                <option value="Africa/Kampala">Africa/Kampala (EAT)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rate Limiting & Analytics -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-chart-line mr-2 text-orange-500"></i>
            Rate Limiting & Analytics
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Messages Per Hour</label>
                <input type="number" name="rate_limit_hour" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                       placeholder="100" 
                       x-model="config.rate_limit_hour">
                <p class="text-xs text-gray-500 mt-1">Maximum messages per hour</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Messages Per Day</label>
                <input type="number" name="rate_limit_day" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                       placeholder="1000" 
                       x-model="config.rate_limit_day">
                <p class="text-xs text-gray-500 mt-1">Maximum messages per day</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Analytics Tracking</label>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="track_delivery" value="1" 
                               class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                               x-model="config.track_delivery">
                        <label for="track_delivery" class="ml-2 text-sm font-medium text-gray-700">
                            Track Delivery Status
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="track_clicks" value="1" 
                               class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                               x-model="config.track_clicks">
                        <label for="track_clicks" class="ml-2 text-sm font-medium text-gray-700">
                            Track Link Clicks
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security & Compliance -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-shield-alt mr-2 text-red-500"></i>
            Security & Compliance
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Content Filtering</label>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="filter_spam" value="1" 
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                               x-model="config.filter_spam">
                        <label for="filter_spam" class="ml-2 text-sm font-medium text-gray-700">
                            Enable Spam Filtering
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="filter_profanity" value="1" 
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                               x-model="config.filter_profanity">
                        <label for="filter_profanity" class="ml-2 text-sm font-medium text-gray-700">
                            Enable Profanity Filter
                        </label>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Compliance Settings</label>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="gdpr_compliance" value="1" 
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                               x-model="config.gdpr_compliance">
                        <label for="gdpr_compliance" class="ml-2 text-sm font-medium text-gray-700">
                            GDPR Compliance Mode
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="opt_out_required" value="1" 
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                               x-model="config.opt_out_required">
                        <label for="opt_out_required" class="ml-2 text-sm font-medium text-gray-700">
                            Require Opt-in Confirmation
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Webhook Configuration -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-link mr-2 text-purple-500"></i>
            Webhook Configuration
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Report URL</label>
                <input type="url" name="delivery_webhook" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                       placeholder="https://yourapp.com/webhook/delivery" 
                       x-model="config.delivery_webhook">
                <p class="text-xs text-gray-500 mt-1">URL to receive delivery status updates</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reply Webhook URL</label>
                <input type="url" name="reply_webhook" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                       placeholder="https://yourapp.com/webhook/reply" 
                       x-model="config.reply_webhook">
                <p class="text-xs text-gray-500 mt-1">URL to receive message replies</p>
            </div>
        </div>
        
        <div class="mt-4">
            <button @click="testWebhooks()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-plug mr-2"></i>Test Webhook Configuration
            </button>
        </div>
    </div>

    <!-- SMS Service Types -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-globe text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Internet SMS</h3>
                <p class="text-sm text-gray-600 mt-2">Standard SMS via internet gateway</p>
                <button @click="selectServiceType('internet')" 
                        :class="serviceType === 'internet' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                        class="mt-4 px-4 py-2 rounded-lg transition-colors">
                    Select
                </button>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-mobile-alt text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">WhatsApp SMS</h3>
                <p class="text-sm text-gray-600 mt-2">Send SMS via WhatsApp Business API</p>
                <button @click="selectServiceType('whatsapp')" 
                        :class="serviceType === 'whatsapp' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'"
                        class="mt-4 px-4 py-2 rounded-lg transition-colors">
                    Select
                </button>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-sim-card text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Mobile SMS</h3>
                <p class="text-sm text-gray-600 mt-2">Direct mobile network SMS</p>
                <button @click="selectServiceType('mobile')" 
                        :class="serviceType === 'mobile' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700'"
                        class="mt-4 px-4 py-2 rounded-lg transition-colors">
                    Select
                </button>
            </div>
        </div>
    </div>

    <!-- Configuration Form -->
    <div x-show="serviceType" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <span x-text="getServiceTypeTitle()"></span> Configuration
            </h2>
        </div>
        
        <form action="{{ route('settings.communication.sms.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Basic Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1 text-blue-500"></i>Configuration Name
                    </label>
                    <input type="text" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Primary SMS, WhatsApp Business" 
                           x-model="config.name">
                    <p class="text-xs text-gray-500 mt-1">A descriptive name for this SMS configuration</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-1 text-green-500"></i>Default Sender ID
                    </label>
                    <input type="text" name="sender_id" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="TANZANIATIP" 
                           x-model="config.sender_id">
                    <p class="text-xs text-gray-500 mt-1">Registered sender ID for SMS messages</p>
                </div>
            </div>

            <!-- Service-specific Settings -->
            <div x-show="serviceType === 'internet'" class="border-t border-gray-200 pt-6">
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-globe mr-2 text-blue-500"></i>
                    Internet SMS Settings
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">API Endpoint</label>
                        <select name="api_endpoint" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                x-model="config.api_endpoint">
                            <option value="/api/sms/v2/text/single">Single Message</option>
                            <option value="/api/sms/v2/text/multi">Multiple Messages</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">API endpoint for SMS sending</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select name="priority" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                x-model="config.priority">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Message delivery priority</p>
                    </div>
                </div>
            </div>

            <div x-show="serviceType === 'whatsapp'" class="border-t border-gray-200 pt-6">
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fab fa-whatsapp mr-2 text-green-500"></i>
                    WhatsApp Business Settings
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp API Version</label>
                        <select name="whatsapp_version" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                x-model="config.whatsapp_version">
                            <option value="v2">API V2</option>
                            <option value="v1">API V1</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">WhatsApp Business API version</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Template Support</label>
                        <div class="flex items-center">
                            <input type="checkbox" name="template_support" value="1" 
                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                   x-model="config.template_support">
                            <label for="template_support" class="ml-2 text-sm font-medium text-gray-700">
                                Enable Message Templates
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Use pre-approved WhatsApp templates</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Webhook URL</label>
                        <input type="url" name="webhook_url" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="https://yourapp.com/webhook" 
                               x-model="config.webhook_url">
                        <p class="text-xs text-gray-500 mt-1">URL for WhatsApp webhook callbacks</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number Validation</label>
                        <select name="number_validation" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                x-model="config.number_validation">
                            <option value="strict">Strict</option>
                            <option value="lenient">Lenient</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Phone number validation mode</p>
                    </div>
                </div>
            </div>

            <div x-show="serviceType === 'mobile'" class="border-t border-gray-200 pt-6">
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-sim-card mr-2 text-purple-500"></i>
                    Mobile Network Settings
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Network Operator</label>
                        <select name="network_operator" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                x-model="config.network_operator">
                            <option value="vodacom">Vodacom</option>
                            <option value="airtel">Airtel</option>
                            <option value="halotel">Halotel</option>
                            <option value="ttc">TTCL</option>
                            <option value="safaricom">Safaricom</option>
                            <option value="other">Other</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Mobile network operator</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SIM Card Number</label>
                        <input type="text" name="sim_card" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="SIM1" 
                               x-model="config.sim_card">
                        <p class="text-xs text-gray-500 mt-1">SIM card identifier</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">APN Settings</label>
                        <input type="text" name="apn" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="internet" 
                               x-model="config.apn">
                        <p class="text-xs text-gray-500 mt-1">Access Point Name (APN)</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">USSD Code</label>
                        <input type="text" name="ussd_code" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="*123#" 
                               x-model="config.ussd_code">
                        <p class="text-xs text-gray-500 mt-1">USSD code for balance checking</p>
                    </div>
                </div>
            </div>

            <!-- API Integration Settings (Common for all types) -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-plug mr-2 text-orange-500"></i>
                    Messaging Service API Integration
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">API Base URL</label>
                        <input type="url" name="api_base_url" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               value="https://messaging-service.co.tz" 
                               x-model="config.api_base_url">
                        <p class="text-xs text-gray-500 mt-1">Base URL for Messaging Service API</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">API Token</label>
                            <div class="relative">
                                <input type="password" name="api_token" required
                                       class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                       placeholder="YOUR_ACCESS_TOKEN" 
                                       x-model="config.api_token">
                                <button type="button" @click="toggleTokenVisibility()" 
                                        class="absolute inset-y-0 right-0 px-3 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
                                    <i :class="showToken ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Bearer token for API authentication</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Default Sender ID</label>
                            <input type="text" name="sender_id" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="TANZANIATIP" 
                                   x-model="config.sender_id">
                            <p class="text-xs text-gray-500 mt-1">Default sender ID for SMS messages</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-cog mr-2 text-gray-500"></i>
                    Additional Settings
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_primary" value="1" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   x-model="config.is_primary">
                            <label for="is_primary" class="ml-2 text-sm font-medium text-gray-700">
                                Set as Primary Configuration
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">Mark this as the default SMS configuration</p>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <input type="checkbox" name="enable_test_mode" value="1" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   x-model="config.enable_test_mode">
                            <label for="enable_test_mode" class="ml-2 text-sm font-medium text-gray-700">
                                Enable Test Mode
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">Send test SMS instead of real ones</p>
                    </div>
                </div>
            </div>

            <!-- Test Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-vial mr-2 text-green-500"></i>
                    Test Configuration
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Phone Number</label>
                        <input type="tel" name="test_phone" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="+255716718040" 
                               x-model="testPhone">
                        <p class="text-xs text-gray-500 mt-1">Phone number to send test SMS (with country code)</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Message</label>
                        <textarea name="test_message" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="This is a test SMS to verify your configuration." 
                                  x-model="testMessage"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Custom test message content</p>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" @click="sendTestSMS()" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Send Test SMS
                    </button>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('settings.communication') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 text-white rounded-lg hover:bg-blue-700 transition-colors" 
                        style="background-color: #009245;">
                    <i class="fas fa-save mr-2"></i>Save Configuration
                </button>
            </div>
        </form>
    </div>

    <!-- API Instructions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-code mr-2 text-indigo-500"></i>
            API Integration Instructions
        </h3>
        
        <div class="space-y-6">
            <!-- Internet SMS API -->
            <div x-show="serviceType === 'internet'" class="border-l-4 border-blue-200 rounded-lg p-4">
                <h4 class="text-md font-medium text-gray-800 mb-2">Internet SMS API</h4>
                <div class="bg-gray-900 text-gray-100 p-3 rounded font-mono text-sm overflow-x-auto">
                    <pre>POST https://messaging-service.co.tz/api/sms/v2/text/single
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_ACCESS_TOKEN

{
    "from": "TANZANIATIP",
    "to": "255716718040",
    "text": "Your SMS message here"
}</pre>
                </div>
            </div>

            <!-- WhatsApp SMS API -->
            <div x-show="serviceType === 'whatsapp'" class="border-l-4 border-green-200 rounded-lg p-4">
                <h4 class="text-md font-medium text-gray-800 mb-2">WhatsApp SMS API</h4>
                <div class="bg-gray-900 text-gray-100 p-3 rounded font-mono text-sm overflow-x-auto">
                    <pre>POST https://messaging-service.co.tz/api/whatsapp/v2/text/single
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_ACCESS_TOKEN

{
    "from": "TANZANIATIP",
    "to": "255716718040",
    "text": "Your WhatsApp message here"
}</pre>
                </div>
            </div>

            <!-- Mobile SMS API -->
            <div x-show="serviceType === 'mobile'" class="border-l-4 border-purple-200 rounded-lg p-4">
                <h4 class="text-md font-medium text-gray-800 mb-2">Mobile SMS API</h4>
                <div class="bg-gray-900 text-gray-100 p-3 rounded font-mono text-sm overflow-x-auto">
                    <pre>POST https://messaging-service.co.tz/api/mobile/v2/text/single
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_ACCESS_TOKEN

{
    "from": "TANZANIATIP",
    "to": "255716718040",
    "text": "Your Mobile SMS message here"
}</pre>
                </div>
            </div>

            <!-- Multiple Messages -->
            <div class="border-l-4 border-orange-200 rounded-lg p-4">
                <h4 class="text-md font-medium text-gray-800 mb-2">Multiple Messages</h4>
                <div class="bg-gray-900 text-gray-100 p-3 rounded font-mono text-sm overflow-x-auto">
                    <pre>POST https://messaging-service.co.tz/api/sms/v2/text/multi
Content-Type: application/json
Accept: application/json
Authorization: Bearer YOUR_ACCESS_TOKEN

{
    "from": "TANZANIATIP",
    "to": [
        "255716718040",
        "255755012345",
        "255767245612"
    ],
    "text": "Your message to multiple recipients"
}</pre>
                </div>
            </div>

            <!-- Response Handling -->
            <div>
                <h4 class="text-md font-medium text-gray-800 mb-2">Response Handling</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-700 mb-2">Expected response format:</p>
                    <div class="bg-gray-900 text-gray-100 p-3 rounded font-mono text-sm overflow-x-auto">
                        <pre>{
    "messages": [
        {
            "to": "255716718040",
            "status": {
                "groupId": 18,
                "groupName": "PENDING",
                "id": 51,
                "name": "ENROUTE (SENT)",
                "description": "Message sent to next instance"
            },
            "messageId": "unique-message-id",
            "message": "Your SMS content here",
            "smsCount": 1,
            "price": 16
        }
    ]
}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function smsConfig() {
    return {
        serviceType: null,
        config: {
            name: '',
            sender_id: 'TANZANIATIP',
            api_endpoint: '/api/sms/v2/text/single',
            priority: 'normal',
            whatsapp_version: 'v2',
            template_support: false,
            webhook_url: '',
            number_validation: 'strict',
            network_operator: 'safaricom',
            sim_card: 'SIM1',
            apn: 'internet',
            ussd_code: '*123#',
            api_base_url: 'https://messaging-service.co.tz',
            api_token: '',
            is_primary: false,
            enable_test_mode: false,
            // Advanced options
            enable_templates: false,
            default_template: '',
            enable_scheduling: false,
            default_send_time: '09:00',
            timezone: 'Africa/Nairobi',
            rate_limit_hour: 100,
            rate_limit_day: 1000,
            track_delivery: true,
            track_clicks: false,
            filter_spam: true,
            filter_profanity: false,
            gdpr_compliance: false,
            opt_out_required: false,
            delivery_webhook: '',
            reply_webhook: ''
        },
        testPhone: '+255716718040',
        testMessage: 'This is a test SMS to verify your SMS configuration.',
        showToken: false,
        
        selectServiceType(type) {
            this.serviceType = type;
            
            // Reset config based on service type
            if (type === 'internet') {
                this.config.api_endpoint = '/api/sms/v2/text/single';
            } else if (type === 'whatsapp') {
                this.config.api_endpoint = '/api/whatsapp/v2/text/single';
            } else if (type === 'mobile') {
                this.config.api_endpoint = '/api/mobile/v2/text/single';
            }
        },
        
        getServiceTypeTitle() {
            const titles = {
                'internet': 'Internet SMS',
                'whatsapp': 'WhatsApp SMS',
                'mobile': 'Mobile SMS'
            };
            return titles[this.serviceType] || 'SMS Configuration';
        },
        
        toggleTokenVisibility() {
            this.showToken = !this.showToken;
        },
        
        testConfiguration() {
            if (!this.config.api_token || !this.config.sender_id) {
                alert('Please configure API token and sender ID first.');
                return;
            }
            
            alert('Testing SMS configuration...\n\nThis will send a test SMS using your configured settings.');
        },
        
        sendTestSMS() {
            if (!this.testPhone) {
                alert('Please enter a test phone number.');
                return;
            }
            
            // Determine API endpoint based on service type
            let endpoint = this.config.api_base_url;
            if (this.serviceType === 'internet') {
                endpoint += '/api/sms/v2/test/text/single';
            } else if (this.serviceType === 'whatsapp') {
                endpoint += '/api/whatsapp/v2/test/text/single';
            } else if (this.serviceType === 'mobile') {
                endpoint += '/api/mobile/v2/test/text/single';
            }
            
            // Simulate API call
            const payload = {
                from: this.config.sender_id,
                to: this.testPhone,
                text: this.testMessage
            };
            
            console.log('Sending test SMS:', payload);
            
            // In a real implementation, this would make an actual API call
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.config.api_token}`
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages[0]) {
                    const status = data.messages[0].status;
                    if (status.groupId === 18) {
                        alert('✅ Test SMS sent successfully!\n\nMessage ID: ' + data.messages[0].messageId + '\nStatus: ' + status.name + '\nCost: ' + data.messages[0].price + ' credits');
                    } else {
                        alert('❌ Test SMS failed!\n\nStatus: ' + status.name + '\nDescription: ' + status.description);
                    }
                } else {
                    alert('❌ Test SMS failed!\n\nUnexpected response format.');
                }
            })
            .catch(error => {
                alert('❌ Test SMS failed!\n\nError: ' + error.message);
            });
        },
        
        showTemplates() {
            alert('Message Templates Manager\n\nAvailable templates:\n• Welcome Message\n• Verification Code\n• Appointment Reminder\n• Payment Confirmation\n• Promotional Message\n\nThis would open a template management interface.');
        },
        
        showSchedulePreview() {
            if (!this.config.enable_scheduling) {
                alert('Please enable message scheduling first.');
                return;
            }
            
            alert('Schedule Preview\n\nDefault Send Time: ' + this.config.default_send_time + '\nTime Zone: ' + this.config.timezone + '\n\nMessages will be sent at the specified time in the selected timezone.');
        },
        
        testWebhooks() {
            if (!this.config.delivery_webhook && !this.config.reply_webhook) {
                alert('Please configure at least one webhook URL first.');
                return;
            }
            
            alert('Testing webhook configuration...\n\nDelivery Webhook: ' + (this.config.delivery_webhook || 'Not configured') + '\nReply Webhook: ' + (this.config.reply_webhook || 'Not configured') + '\n\nThis would send test webhook payloads to your configured URLs.');
        },
        
        validateConfiguration() {
            const errors = [];
            
            // Validate rate limits
            if (this.config.rate_limit_hour > 1000) {
                errors.push('Hourly rate limit should not exceed 1000 messages');
            }
            
            if (this.config.rate_limit_day > 24000) {
                errors.push('Daily rate limit should not exceed 24,000 messages');
            }
            
            // Validate webhook URLs
            if (this.config.delivery_webhook && !this.isValidUrl(this.config.delivery_webhook)) {
                errors.push('Delivery webhook URL is not valid');
            }
            
            if (this.config.reply_webhook && !this.isValidUrl(this.config.reply_webhook)) {
                errors.push('Reply webhook URL is not valid');
            }
            
            // Validate compliance settings
            if (this.config.gdpr_compliance && !this.config.opt_out_required) {
                errors.push('GDPR compliance requires opt-in confirmation');
            }
            
            if (errors.length > 0) {
                alert('Configuration Validation Errors:\n\n' + errors.join('\n'));
                return false;
            }
            
            return true;
        },
        
        isValidUrl(string) {
            try {
                new URL(string);
                return true;
            } catch (_) {
                return false;
            }
        },
        
        generateWebhookSecret() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
            for (let i = 0; i < 32; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return result;
        }
    }
}
</script>
@endsection
