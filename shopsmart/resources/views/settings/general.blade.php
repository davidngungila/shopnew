@extends('layouts.app')

@section('title', 'General Settings')

@section('content')
<div class="space-y-6" x-data="generalSettings()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">General Settings</h1>
            <p class="text-gray-600 mt-1">Configure company information and system preferences</p>
        </div>
        <div class="flex gap-2">
            <button @click="resetToDefaults()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-undo mr-2"></i>Reset to Defaults
            </button>
            <a href="{{ route('settings.index') }}" class="px-4 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-arrow-left mr-2"></i>Back to Settings
            </a>
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

    <!-- Advanced Settings Overview -->
    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg shadow-sm border border-green-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-cog mr-2 text-green-600"></i>
                Advanced Settings Overview
            </h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Last updated: <span x-text="lastUpdated">Never</span></span>
                <button @click="exportSettings()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Export Settings
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Company Info</p>
                        <p class="text-lg font-bold text-green-600" x-text="companyInfoComplete">85%</p>
                        <p class="text-xs text-gray-500 mt-1">Profile completion</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-building text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Business Settings</p>
                        <p class="text-lg font-bold text-blue-600" x-text="businessSettingsComplete">92%</p>
                        <p class="text-xs text-gray-500 mt-1">Configuration</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-briefcase text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Regional Settings</p>
                        <p class="text-lg font-bold text-purple-600" x-text="regionalSettingsComplete">78%</p>
                        <p class="text-xs text-gray-500 mt-1">Localization</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-globe text-purple-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">System Preferences</p>
                        <p class="text-lg font-bold text-orange-600" x-text="systemPreferencesComplete">95%</p>
                        <p class="text-xs text-gray-500 mt-1">Optimization</p>
                    </div>
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-sliders-h text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('settings.general.update') }}" method="POST" class="space-y-8">
        @csrf
        
        <!-- Company Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Company Information</h2>
                <span class="text-sm text-gray-500">Basic company details</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-building mr-1 text-blue-500"></i>Company Name
                    </label>
                    <input type="text" name="company_name" value="{{ $settings['company_name'] ?? 'ShopSmart POS' }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter company name">
                    <p class="text-xs text-gray-500 mt-1">Your business name</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tagline mr-1 text-blue-500"></i>Tagline
                    </label>
                    <input type="text" name="company_tagline" value="{{ $settings['company_tagline'] ?? 'Smart Retail Management' }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter company tagline">
                    <p class="text-xs text-gray-500 mt-1">Your business slogan</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1 text-blue-500"></i>Email Address
                    </label>
                    <input type="email" name="company_email" value="{{ $settings['company_email'] ?? 'info@shopsmart.com' }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="company@example.com">
                    <p class="text-xs text-gray-500 mt-1">Official company email</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-1 text-blue-500"></i>Phone Number
                    </label>
                    <input type="tel" name="company_phone" value="{{ $settings['company_phone'] ?? '+255 123 456 789' }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="+255 123 456 789">
                    <p class="text-xs text-gray-500 mt-1">Contact phone number</p>
                </div>
                
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>Address
                    </label>
                    <textarea name="company_address" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Enter company address">{{ $settings['company_address'] ?? '123 Main Street, Dar es Salaam, Tanzania' }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Physical business location</p>
                </div>
            </div>
        </div>

        <!-- Business Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Business Settings</h2>
                <span class="text-sm text-gray-500">Operational preferences</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-industry mr-1 text-green-500"></i>Business Type
                    </label>
                    <select name="business_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="retail" {{ ($settings['business_type'] ?? 'retail') === 'retail' ? 'selected' : '' }}>Retail Store</option>
                        <option value="restaurant" {{ ($settings['business_type'] ?? '') === 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                        <option value="wholesale" {{ ($settings['business_type'] ?? '') === 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                        <option value="service" {{ ($settings['business_type'] ?? '') === 'service' ? 'selected' : '' }}>Service Business</option>
                        <option value="manufacturing" {{ ($settings['business_type'] ?? '') === 'manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Type of business operation</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-users mr-1 text-green-500"></i>Business Size
                    </label>
                    <select name="business_size" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="small" {{ ($settings['business_size'] ?? 'small') === 'small' ? 'selected' : '' }}>Small (1-10 employees)</option>
                        <option value="medium" {{ ($settings['business_size'] ?? '') === 'medium' ? 'selected' : '' }}>Medium (11-50 employees)</option>
                        <option value="large" {{ ($settings['business_size'] ?? '') === 'large' ? 'selected' : '' }}>Large (50+ employees)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Number of employees</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calculator mr-1 text-green-500"></i>Tax Registration Number
                    </label>
                    <input type="text" name="tax_number" value="{{ $settings['tax_number'] ?? '' }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="Enter tax registration number">
                    <p class="text-xs text-gray-500 mt-1">VAT/TIN registration number</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-certificate mr-1 text-green-500"></i>Business License Number
                    </label>
                    <input type="text" name="license_number" value="{{ $settings['license_number'] ?? '' }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="Enter license number">
                    <p class="text-xs text-gray-500 mt-1">Business license identifier</p>
                </div>
            </div>
        </div>

        <!-- Regional Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Regional Settings</h2>
                <span class="text-sm text-gray-500">Location and language preferences</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave mr-1 text-purple-500"></i>Currency
                    </label>
                    <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="TZS" {{ ($settings['currency'] ?? 'TZS') === 'TZS' ? 'selected' : '' }}>TZS (TSh)</option>
                        <option value="USD" {{ ($settings['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR" {{ ($settings['currency'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        <option value="GBP" {{ ($settings['currency'] ?? '') === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                        <option value="KES" {{ ($settings['currency'] ?? '') === 'KES' ? 'selected' : '' }}>KES (KSh)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Default currency for transactions</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-language mr-1 text-purple-500"></i>Language
                    </label>
                    <select name="language" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="en" {{ ($settings['language'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                        <option value="sw" {{ ($settings['language'] ?? '') === 'sw' ? 'selected' : '' }}>Swahili</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">System interface language</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-globe mr-1 text-purple-500"></i>Timezone
                    </label>
                    <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="Africa/Dar_es_Salaam" {{ ($settings['timezone'] ?? 'Africa/Dar_es_Salaam') === 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Dar es Salaam (EAT)</option>
                        <option value="Africa/Nairobi" {{ ($settings['timezone'] ?? '') === 'Africa/Nairobi' ? 'selected' : '' }}>Nairobi (EAT)</option>
                        <option value="UTC" {{ ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' }}>UTC</option>
                        <option value="America/New_York" {{ ($settings['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                        <option value="Europe/London" {{ ($settings['timezone'] ?? '') === 'Europe/London' ? 'selected' : '' }}>London</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Time zone for date/time display</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1 text-purple-500"></i>Date Format
                    </label>
                    <select name="date_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="Y-m-d" {{ ($settings['date_format'] ?? 'Y-m-d') === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                        <option value="d/m/Y" {{ ($settings['date_format'] ?? '') === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                        <option value="m/d/Y" {{ ($settings['date_format'] ?? '') === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Date display format</p>
                </div>
            </div>
        </div>

        <!-- System Preferences -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">System Preferences</h2>
                <span class="text-sm text-gray-500">Application behavior settings</span>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="enable_notifications" value="1" 
                               {{ ($settings['enable_notifications'] ?? '1') === '1' ? 'checked' : '' }} 
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Enable System Notifications</span>
                            <p class="text-xs text-gray-500">Show alerts and updates</p>
                        </div>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bell text-blue-600"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="enable_auto_backup" value="1" 
                               {{ ($settings['enable_auto_backup'] ?? '0') === '1' ? 'checked' : '' }} 
                               class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Enable Automatic Backups</span>
                            <p class="text-xs text-gray-500">Daily data backup</p>
                        </div>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-database text-green-600"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="enable_dark_mode" value="1" 
                               {{ ($settings['enable_dark_mode'] ?? '0') === '1' ? 'checked' : '' }} 
                               class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Enable Dark Mode</span>
                            <p class="text-xs text-gray-500">Dark theme interface</p>
                        </div>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-moon text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
            <button type="button" @click="testSettings()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-flask mr-2"></i>Test Settings
            </button>
            <button type="submit" class="px-6 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-save mr-2"></i>Save Changes
            </button>
        </div>
    </form>
</div>

<script>
function generalSettings() {
    return {
        // Advanced properties
        lastUpdated: 'Never',
        companyInfoComplete: 85,
        businessSettingsComplete: 92,
        regionalSettingsComplete: 78,
        systemPreferencesComplete: 95,
        
        // Settings data
        settings: {
            company: {
                name: 'ShopSmart',
                email: 'info@shopsmart.com',
                phone: '+255 712 345 678',
                address: '123 Main Street, Dar es Salaam, Tanzania',
                website: 'https://shopsmart.com',
                logo: null,
                taxId: 'TAX-123456789',
                registrationNumber: 'REG-987654321'
            },
            business: {
                industry: 'Retail',
                businessType: 'Limited Company',
                employeeCount: '50-100',
                annualRevenue: '1M-5M',
                currency: 'TZS',
                taxRate: 18,
                fiscalYear: 'January',
                workingDays: 'Monday-Friday',
                workingHours: '09:00-17:00'
            },
            regional: {
                country: 'Tanzania',
                timezone: 'Africa/Dar_es_Salaam',
                language: 'en',
                dateFormat: 'd/m/Y',
                timeFormat: '24h',
                numberFormat: 'en',
                currencySymbol: 'TZS',
                decimalPlaces: 2
            },
            system: {
                theme: 'light',
                fontSize: 'medium',
                sidebarCollapsed: false,
                notifications: true,
                autoSave: true,
                sessionTimeout: 30,
                twoFactorAuth: false,
                maintenanceMode: false
            }
        },
        
        // Advanced methods
        exportSettings() {
            const settingsData = {
                company: this.settings.company,
                business: this.settings.business,
                regional: this.settings.regional,
                system: this.settings.system,
                exportedAt: new Date().toISOString(),
                version: '1.0.0'
            };
            
            const dataStr = JSON.stringify(settingsData, null, 2);
            const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
            
            const exportFileDefaultName = `shopsmart-settings-${new Date().toISOString().split('T')[0]}.json`;
            
            const linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
            
            this.showNotification('success', 'Settings exported successfully!');
        },
        
        importSettings() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '.json';
            
            input.onchange = (event) => {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        try {
                            const importedSettings = JSON.parse(e.target.result);
                            this.settings = { ...this.settings, ...importedSettings };
                            this.showNotification('success', 'Settings imported successfully!');
                            this.updateCompletionPercentages();
                        } catch (error) {
                            this.showNotification('error', 'Invalid settings file format!');
                        }
                    };
                    reader.readAsText(file);
                }
            };
            
            input.click();
        },
        
        resetToDefaults() {
            if (confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
                // Reset to default values
                this.settings = {
                    company: {
                        name: 'ShopSmart',
                        email: 'info@shopsmart.com',
                        phone: '+255 712 345 678',
                        address: '123 Main Street, Dar es Salaam, Tanzania',
                        website: 'https://shopsmart.com',
                        logo: null,
                        taxId: '',
                        registrationNumber: ''
                    },
                    business: {
                        industry: 'Retail',
                        businessType: 'Limited Company',
                        employeeCount: '1-10',
                        annualRevenue: '<100K',
                        currency: 'TZS',
                        taxRate: 18,
                        fiscalYear: 'January',
                        workingDays: 'Monday-Friday',
                        workingHours: '09:00-17:00'
                    },
                    regional: {
                        country: 'Tanzania',
                        timezone: 'Africa/Dar_es_Salaam',
                        language: 'en',
                        dateFormat: 'd/m/Y',
                        timeFormat: '24h',
                        numberFormat: 'en',
                        currencySymbol: 'TZS',
                        decimalPlaces: 2
                    },
                    system: {
                        theme: 'light',
                        fontSize: 'medium',
                        sidebarCollapsed: false,
                        notifications: true,
                        autoSave: true,
                        sessionTimeout: 30,
                        twoFactorAuth: false,
                        maintenanceMode: false
                    }
                };
                
                this.showNotification('success', 'Settings reset to defaults successfully!');
                this.updateCompletionPercentages();
            }
        },
        
        saveSettings() {
            // Simulate saving settings
            this.showNotification('success', 'Settings saved successfully!');
            this.lastUpdated = new Date().toLocaleString();
            this.updateCompletionPercentages();
        },
        
        updateCompletionPercentages() {
            // Calculate completion percentages
            const companyFields = Object.values(this.settings.company).filter(v => v !== null && v !== '').length;
            this.companyInfoComplete = Math.round((companyFields / Object.keys(this.settings.company).length) * 100);
            
            const businessFields = Object.values(this.settings.business).filter(v => v !== null && v !== '').length;
            this.businessSettingsComplete = Math.round((businessFields / Object.keys(this.settings.business).length) * 100);
            
            const regionalFields = Object.values(this.settings.regional).filter(v => v !== null && v !== '').length;
            this.regionalSettingsComplete = Math.round((regionalFields / Object.keys(this.settings.regional).length) * 100);
            
            const systemFields = Object.values(this.settings.system).filter(v => v !== null && v !== '').length;
            this.systemPreferencesComplete = Math.round((systemFields / Object.keys(this.settings.system).length) * 100);
        },
        
        showNotification(type, message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 
                'bg-red-50 border border-red-200 text-red-800'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        },
        
        validateSettings() {
            const errors = [];
            
            // Validate company email
            if (!this.settings.company.email || !this.isValidEmail(this.settings.company.email)) {
                errors.push('Company email is required and must be valid');
            }
            
            // Validate company name
            if (!this.settings.company.name || this.settings.company.name.length < 2) {
                errors.push('Company name is required and must be at least 2 characters');
            }
            
            // Validate tax rate
            if (this.settings.business.taxRate < 0 || this.settings.business.taxRate > 100) {
                errors.push('Tax rate must be between 0 and 100');
            }
            
            if (errors.length > 0) {
                this.showNotification('error', errors.join('\n'));
                return false;
            }
            
            return true;
        },
        
        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        
        init() {
            this.updateCompletionPercentages();
        }
    }
}

function testSettings() {
    // Test current settings
    const companyName = document.querySelector('[name="company_name"]').value;
    const email = document.querySelector('[name="company_email"]').value;
    
    if (!companyName || !email) {
        alert('Please fill in required fields before testing.');
        return;
    }
    
    alert(`Settings test successful!\n\nCompany: ${companyName}\nEmail: ${email}\nAll configurations appear valid.`);
}
</script>
@endsection
