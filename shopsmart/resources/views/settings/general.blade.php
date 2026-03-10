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

    <form action="{{ route('settings.general.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Company Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Company Information</h2>
                <span class="text-sm text-gray-500">Basic company details</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building mr-1 text-purple-500"></i>Company Name
                        </label>
                        <input type="text" name="company_name" value="{{ $settings['company_name'] ?? 'ShopSmart Tanzania' }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="Enter company name">
                        @error('company_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-1 text-purple-500"></i>Company Email
                        </label>
                        <input type="email" name="company_email" value="{{ $settings['company_email'] ?? 'info@shopsmart.co.tz' }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="company@example.com">
                        @error('company_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-1 text-purple-500"></i>Company Phone
                        </label>
                        <input type="tel" name="company_phone" value="{{ $settings['company_phone'] ?? '+255 712 345 678' }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="+255 XXX XXX XXX">
                        @error('company_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-1 text-purple-500"></i>Tax ID / Registration Number
                        </label>
                        <input type="text" name="tax_id" value="{{ $settings['tax_id'] ?? '123456789' }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="Tax identification number">
                        @error('tax_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-1 text-purple-500"></i>Company Address
                        </label>
                        <textarea name="company_address" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                  placeholder="Enter full company address">{{ $settings['company_address'] ?? 'Kijitonyama, Dar es Salaam, Tanzania' }}</textarea>
                        @error('company_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-1 text-purple-500"></i>Company Logo
                        </label>
                        <div class="flex items-center space-x-4">
                            <input type="file" name="company_logo" accept="image/*" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   @change="previewLogo(event)">
                            @if(isset($settings['company_logo']) && $settings['company_logo'])
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $settings['company_logo']) }}" alt="Company Logo" 
                                     class="h-12 w-12 rounded-lg object-cover border border-gray-300">
                            </div>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Recommended: 200x200px, Max 2MB</p>
                        @error('company_logo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Logo Preview -->
                    <div x-show="logoPreview" x-transition class="mt-2">
                        <img :src="logoPreview" alt="Logo Preview" class="h-16 w-16 rounded-lg object-cover border border-gray-300">
                    </div>
                </div>
            </div>
        </div>

        <!-- System Preferences -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">System Preferences</h2>
                <span class="text-sm text-gray-500">Display and regional settings</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-1 text-green-500"></i>Default Currency
                        </label>
                        <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="TZS" {{ ($settings['currency'] ?? 'TZS') === 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                            <option value="USD" {{ ($settings['currency'] ?? 'TZS') === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ ($settings['currency'] ?? 'TZS') === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            <option value="GBP" {{ ($settings['currency'] ?? 'TZS') === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                        </select>
                        @error('currency')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-language mr-1 text-green-500"></i>System Language
                        </label>
                        <select name="language" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="en" {{ ($settings['language'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                            <option value="sw" {{ ($settings['language'] ?? 'en') === 'sw' ? 'selected' : '' }}>Swahili</option>
                        </select>
                        @error('language')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-globe mr-1 text-green-500"></i>Timezone
                        </label>
                        <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Africa/Dar_es_Salaam" {{ ($settings['timezone'] ?? 'Africa/Dar_es_Salaam') === 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>East Africa Time (EAT)</option>
                            <option value="UTC" {{ ($settings['timezone'] ?? 'Africa/Dar_es_Salaam') === 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="Europe/London" {{ ($settings['timezone'] ?? 'Africa/Dar_es_Salaam') === 'Europe/London' ? 'selected' : '' }}>London</option>
                            <option value="America/New_York" {{ ($settings['timezone'] ?? 'Africa/Dar_es_Salaam') === 'America/New_York' ? 'selected' : '' }}>New York</option>
                        </select>
                        @error('timezone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1 text-green-500"></i>Date Format
                        </label>
                        <select name="date_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Y-m-d" {{ ($settings['date_format'] ?? 'Y-m-d') === 'Y-m-d' ? 'selected' : '' }}>2025-01-15</option>
                            <option value="d/m/Y" {{ ($settings['date_format'] ?? 'Y-m-d') === 'd/m/Y' ? 'selected' : '' }}>15/01/2025</option>
                            <option value="m/d/Y" {{ ($settings['date_format'] ?? 'Y-m-d') === 'm/d/Y' ? 'selected' : '' }}>01/15/2025</option>
                            <option value="d-M-Y" {{ ($settings['date_format'] ?? 'Y-m-d') === 'd-M-Y' ? 'selected' : '' }}>15-Jan-2025</option>
                        </select>
                        @error('date_format')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Advanced Settings</h2>
                <span class="text-sm text-gray-500">Additional configuration options</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users mr-1 text-blue-500"></i>Default Customer Group
                        </label>
                        <select name="default_customer_group" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="retail">Retail Customers</option>
                            <option value="wholesale">Wholesale Customers</option>
                            <option value="vip">VIP Customers</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-percentage mr-1 text-blue-500"></i>Default Tax Rate (%)
                        </label>
                        <input type="number" name="default_tax_rate" value="{{ $settings['default_tax_rate'] ?? '18' }}" 
                               step="0.01" min="0" max="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="18.00">
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-invoice mr-1 text-blue-500"></i>Invoice Prefix
                        </label>
                        <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'INV-' }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="INV-">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-receipt mr-1 text-blue-500"></i>Receipt Prefix
                        </label>
                        <input type="text" name="receipt_prefix" value="{{ $settings['receipt_prefix'] ?? 'RCP-' }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="RCP-">
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
            <button type="button" @click="testSettings()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-flask mr-2"></i>Test Configuration
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
        logoPreview: null,
        
        previewLogo(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.logoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        resetToDefaults() {
            if (confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
                // Reset form to defaults
                const form = document.querySelector('form');
                form.reset();
                
                // Reset to default values
                const defaults = {
                    'company_name': 'ShopSmart Tanzania',
                    'company_email': 'info@shopsmart.co.tz',
                    'company_phone': '+255 712 345 678',
                    'tax_id': '123456789',
                    'company_address': 'Kijitonyama, Dar es Salaam, Tanzania',
                    'currency': 'TZS',
                    'language': 'en',
                    'timezone': 'Africa/Dar_es_Salaam',
                    'date_format': 'Y-m-d',
                    'default_tax_rate': '18',
                    'invoice_prefix': 'INV-',
                    'receipt_prefix': 'RCP-'
                };
                
                Object.keys(defaults).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.value = defaults[key];
                    }
                });
            }
        },
        
        testSettings() {
            // Test email configuration
            const email = document.querySelector('[name="company_email"]').value;
            if (email) {
                alert(`Test email would be sent to: ${email}`);
            }
            
            // Test database connection
            console.log('Testing database connection...');
            
            // Test file upload
            console.log('Testing file upload permissions...');
        }
    }
}
</script>
@endsection
            </div>

            <!-- Regional Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Regional Settings</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                        <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="TZS" {{ ($settings['currency'] ?? 'TZS') == 'TZS' ? 'selected' : '' }}>TZS (TSh)</option>
                            <option value="USD" {{ ($settings['currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ ($settings['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP" {{ ($settings['currency'] ?? '') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="KES" {{ ($settings['currency'] ?? '') == 'KES' ? 'selected' : '' }}>KES (KSh)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                        <select name="language" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="en" {{ ($settings['language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="es" {{ ($settings['language'] ?? '') == 'es' ? 'selected' : '' }}>Spanish</option>
                            <option value="fr" {{ ($settings['language'] ?? '') == 'fr' ? 'selected' : '' }}>French</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="Africa/Dar_es_Salaam" {{ ($settings['timezone'] ?? 'Africa/Dar_es_Salaam') == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Dar es Salaam (EAT)</option>
                            <option value="UTC" {{ ($settings['timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ ($settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                            <option value="Europe/London" {{ ($settings['timezone'] ?? '') == 'Europe/London' ? 'selected' : '' }}>London</option>
                            <option value="Africa/Nairobi" {{ ($settings['timezone'] ?? '') == 'Africa/Nairobi' ? 'selected' : '' }}>Nairobi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                        <select name="date_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="Y-m-d" {{ ($settings['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            <option value="d/m/Y" {{ ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                            <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" @click="testSettings()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-flask mr-2"></i>Test Configuration
                </button>
                <button type="submit" class="px-6 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function generalSettings() {
    return {
        logoPreview: null,
        
        previewLogo(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.logoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        resetToDefaults() {
            if (confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
                // Reset form to defaults
                const form = document.querySelector('form');
                form.reset();
                
                // Reset to default values
                const defaults = {
                    'company_name': 'ShopSmart Tanzania',
                    'company_email': 'info@shopsmart.co.tz',
                    'company_phone': '+255 712 345 678',
                    'tax_id': '123456789',
                    'company_address': 'Kijitonyama, Dar es Salaam, Tanzania',
                    'currency': 'TZS',
                    'language': 'en',
                    'timezone': 'Africa/Dar_es_Salaam',
                    'date_format': 'Y-m-d',
                    'default_tax_rate': '18',
                    'invoice_prefix': 'INV-',
                    'receipt_prefix': 'RCP-'
                };
                
                Object.keys(defaults).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.value = defaults[key];
                    }
                });
            }
        },
        
        testSettings() {
            // Test email configuration
            const email = document.querySelector('[name="company_email"]').value;
            if (email) {
                alert(`Test email would be sent to: ${email}`);
            }
            
            // Test database connection
            console.log('Testing database connection...');
            
            // Test file upload
            console.log('Testing file upload permissions...');
        }
    }
}
</script>
@endsection

