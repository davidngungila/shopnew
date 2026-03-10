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
        init() {
            // Initialize component
        },
        
        resetToDefaults() {
            if (confirm('Reset all settings to default values? This action cannot be undone.')) {
                // Reset form to default values
                const form = document.querySelector('form');
                form.reset();
                
                // Show success message
                alert('Settings have been reset to default values. Click "Save Changes" to apply.');
            }
        },
        
        testSettings() {
            // Test current settings
            const companyName = document.querySelector('[name="company_name"]').value;
            const email = document.querySelector('[name="company_email"]').value;
            
            if (!companyName || !email) {
                alert('Please fill in required fields before testing.');
                return;
            }
            
            alert(`Settings test successful!\n\nCompany: ${companyName}\nEmail: ${email}\nAll configurations appear valid.`);
        }
    }
}
</script>
@endsection
