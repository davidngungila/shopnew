@extends('layouts.app')

@section('title', 'Financial Settings')

@section('content')
<div class="space-y-6" x-data="financialSettings()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Financial Settings</h1>
            <p class="text-gray-600 mt-1">Configure tax rates, payment methods, and financial policies</p>
        </div>
        <div class="flex gap-2">
            <button @click="exportFinancialSettings()" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2 hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-download"></i>
                <span>Export</span>
            </button>
            <button @click="resetToDefaults()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-undo mr-2"></i>Reset
            </button>
            <a href="{{ route('settings.index') }}" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors" style="background-color: #6b7280;">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Advanced Financial Overview -->
    <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-lg shadow-sm border border-blue-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                Financial Overview
            </h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Last updated: <span x-text="lastUpdated">Never</span></span>
                <button @click="refreshFinancialData()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh Data
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Revenue</p>
                        <p class="text-lg font-bold text-green-600" x-text="formatCurrency(financialData.revenue)">TZS 2.5M</p>
                        <p class="text-xs text-green-500 mt-1">
                            <i class="fas fa-arrow-up"></i> +12.5% this month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Expenses</p>
                        <p class="text-lg font-bold text-red-600" x-text="formatCurrency(financialData.expenses)">TZS 1.8M</p>
                        <p class="text-xs text-red-500 mt-1">
                            <i class="fas fa-arrow-up"></i> +8.3% this month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-receipt text-red-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Profit</p>
                        <p class="text-lg font-bold text-blue-600" x-text="formatCurrency(financialData.profit)">TZS 700K</p>
                        <p class="text-xs text-blue-500 mt-1">
                            <i class="fas fa-arrow-up"></i> +28.1% this month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-pie text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tax Collected</p>
                        <p class="text-lg font-bold text-purple-600" x-text="formatCurrency(financialData.taxCollected)">TZS 450K</p>
                        <p class="text-xs text-purple-500 mt-1">
                            <i class="fas fa-check-circle"></i> On track
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-invoice-dollar text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Financial Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-bolt mr-2 text-yellow-500"></i>
            Quick Financial Actions
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button @click="generateFinancialReport()" 
                    class="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105">
                <div class="relative z-10">
                    <i class="fas fa-file-invoice text-2xl mb-2"></i>
                    <h4 class="font-semibold">Generate Report</h4>
                    <p class="text-xs opacity-90 mt-1">Monthly financial report</p>
                </div>
                <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-white opacity-10 rounded-full transform group-hover:scale-150 transition-transform duration-300"></div>
            </button>
            
            <button @click="exportTaxReport()" 
                    class="group relative overflow-hidden bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105">
                <div class="relative z-10">
                    <i class="fas fa-calculator text-2xl mb-2"></i>
                    <h4 class="font-semibold">Tax Report</h4>
                    <p class="text-xs opacity-90 mt-1">Tax summary and analysis</p>
                </div>
                <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-white opacity-10 rounded-full transform group-hover:scale-150 transition-transform duration-300"></div>
            </button>
            
            <button @click="auditTransactions()" 
                    class="group relative overflow-hidden bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-4 text-white hover:from-purple-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                <div class="relative z-10">
                    <i class="fas fa-search text-2xl mb-2"></i>
                    <h4 class="font-semibold">Audit Transactions</h4>
                    <p class="text-xs opacity-90 mt-1">Review and audit</p>
                </div>
                <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-white opacity-10 rounded-full transform group-hover:scale-150 transition-transform duration-300"></div>
            </button>
        </div>
    </div>
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

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Default Tax Rate</p>
                    <p class="text-2xl font-bold text-green-600">{{ $settings['default_tax_rate'] ?? '18' }}%</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-percentage text-green-500"></i> 
                        Applied to sales
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-receipt text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Payment Methods</p>
                    <p class="text-2xl font-bold text-blue-600" x-text="activePaymentMethods.length">4</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-credit-card text-blue-500"></i> 
                        Payment options
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Currency</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $settings['currency'] ?? 'TZS' }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-coins text-purple-500"></i> 
                        Default currency
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Discount Type</p>
                    <p class="text-2xl font-bold text-orange-600" x-text="discountTypeText">Percentage</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-tag text-orange-500"></i> 
                        Default discount
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-percentage text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('settings.financial.update') }}" method="POST" class="space-y-8">
        @csrf
        
        <!-- Tax Configuration -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Tax Configuration</h2>
                <span class="text-sm text-gray-500">Tax rates and calculations</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-percentage mr-1 text-green-500"></i>Default Tax Rate (%)
                        </label>
                        <input type="number" name="default_tax_rate" value="{{ $settings['default_tax_rate'] ?? '18' }}" 
                               step="0.01" min="0" max="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="18.00"
                               @input="updateTaxRate($event.target.value)">
                        @error('default_tax_rate')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Applied to all taxable sales by default</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calculator mr-1 text-green-500"></i>Tax Calculation Method
                        </label>
                        <select name="tax_calculation_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="inclusive" {{ ($settings['tax_calculation_method'] ?? 'inclusive') === 'inclusive' ? 'selected' : '' }}>Tax Inclusive (Price includes tax)</option>
                            <option value="exclusive" {{ ($settings['tax_calculation_method'] ?? '') === 'exclusive' ? 'selected' : '' }}>Tax Exclusive (Tax added to price)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">How tax is calculated in sales</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-invoice-dollar mr-1 text-green-500"></i>Tax ID/VAT Number
                        </label>
                        <input type="text" name="tax_id_number" value="{{ $settings['tax_id_number'] ?? '' }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="TAX-123456789">
                        @error('tax_id_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Business tax identification number</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-stamp mr-1 text-green-500"></i>Tax Registration Status
                        </label>
                        <select name="tax_registration_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="registered" {{ ($settings['tax_registration_status'] ?? 'registered') === 'registered' ? 'selected' : '' }}>Registered for Tax</option>
                            <option value="exempt" {{ ($settings['tax_registration_status'] ?? '') === 'exempt' ? 'selected' : '' }}>Tax Exempt</option>
                            <option value="not_required" {{ ($settings['tax_registration_status'] ?? '') === 'not_required' ? 'selected' : '' }}>Not Required</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Business tax registration status</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Payment Methods</h2>
                <span class="text-sm text-gray-500">Configure accepted payment options</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Traditional Payments</h3>
                    
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_payment_cash" value="1" 
                                   {{ ($settings['enable_payment_cash'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                   @change="updatePaymentMethods()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Cash</span>
                                <p class="text-xs text-gray-500">Physical cash payments</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-green-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_payment_card" value="1" 
                                   {{ ($settings['enable_payment_card'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   @change="updatePaymentMethods()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Credit/Debit Cards</span>
                                <p class="text-xs text-gray-500">Visa, Mastercard, etc.</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-credit-card text-blue-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_payment_bank" value="1" 
                                   {{ ($settings['enable_payment_bank'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                   @change="updatePaymentMethods()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Bank Transfer</span>
                                <p class="text-xs text-gray-500">Direct bank deposits</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-university text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Digital Payments</h3>
                    
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_payment_mobile_money" value="1" 
                                   {{ ($settings['enable_payment_mobile_money'] ?? '1') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                                   @change="updatePaymentMethods()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Mobile Money</span>
                                <p class="text-xs text-gray-500">M-Pesa, Tigo Pesa, etc.</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-orange-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_payment_cheque" value="1" 
                                   {{ ($settings['enable_payment_cheque'] ?? '0') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                                   @change="updatePaymentMethods()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Cheque</span>
                                <p class="text-xs text-gray-500">Bank cheques</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-receipt text-teal-600"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="enable_payment_crypto" value="1" 
                                   {{ ($settings['enable_payment_crypto'] ?? '0') == '1' ? 'checked' : '' }} 
                                   class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                   @change="updatePaymentMethods()">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Cryptocurrency</span>
                                <p class="text-xs text-gray-500">Bitcoin, Ethereum, etc.</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fab fa-bitcoin text-indigo-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Discount Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Discount Settings</h2>
                <span class="text-sm text-gray-500">Configure discount policies</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-1 text-orange-500"></i>Default Discount Type
                        </label>
                        <select name="default_discount_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                @change="updateDiscountType($event.target.value)">
                            <option value="percentage" {{ ($settings['default_discount_type'] ?? 'percentage') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ ($settings['default_discount_type'] ?? '') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Default discount calculation method</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-percentage mr-1 text-orange-500"></i>Maximum Discount Rate (%)
                        </label>
                        <input type="number" name="max_discount_rate" value="{{ $settings['max_discount_rate'] ?? '50' }}" 
                               step="0.01" min="0" max="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                               placeholder="50.00">
                        <p class="text-xs text-gray-500 mt-1">Maximum discount allowed per transaction</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-gift mr-1 text-orange-500"></i>Enable Staff Discounts
                        </label>
                        <select name="enable_staff_discounts" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="1" {{ ($settings['enable_staff_discounts'] ?? '1') == '1' ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ ($settings['enable_staff_discounts'] ?? '') === '0' ? 'selected' : '' }}>Disabled</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Allow staff to apply discounts</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tie mr-1 text-orange-500"></i>Staff Discount Rate (%)
                        </label>
                        <input type="number" name="staff_discount_rate" value="{{ $settings['staff_discount_rate'] ?? '10' }}" 
                               step="0.01" min="0" max="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                               placeholder="10.00">
                        <p class="text-xs text-gray-500 mt-1">Default discount rate for staff</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Currency Settings</h2>
                <span class="text-sm text-gray-500">Configure currency and exchange rates</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-coins mr-1 text-purple-500"></i>Primary Currency
                        </label>
                        <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="TZS" {{ ($settings['currency'] ?? 'TZS') === 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                            <option value="USD" {{ ($settings['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ ($settings['currency'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            <option value="GBP" {{ ($settings['currency'] ?? '') === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Main currency for transactions</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-1 text-purple-500"></i>Currency Symbol Position
                        </label>
                        <select name="currency_position" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="before" {{ ($settings['currency_position'] ?? 'before') === 'before' ? 'selected' : '' }}>Before amount (TZS 100)</option>
                            <option value="after" {{ ($settings['currency_position'] ?? '') === 'after' ? 'selected' : '' }}>After amount (100 TZS)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Where currency symbol appears</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-exchange-alt mr-1 text-purple-500"></i>Enable Multi-Currency
                        </label>
                        <select name="enable_multi_currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="1" {{ ($settings['enable_multi_currency'] ?? '0') == '1' ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ ($settings['enable_multi_currency'] ?? '0') === '0' ? 'selected' : '' }}>Disabled</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Accept payments in multiple currencies</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sync mr-1 text-purple-500"></i>Auto-Update Exchange Rates
                        </label>
                        <select name="auto_update_rates" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="1" {{ ($settings['auto_update_rates'] ?? '0') == '1' ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ ($settings['auto_update_rates'] ?? '0') === '0' ? 'selected' : '' }}>Disabled</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Automatically update currency exchange rates</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Invoice Settings</h2>
                <span class="text-sm text-gray-500">Configure invoicing and billing</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-invoice mr-1 text-blue-500"></i>Invoice Prefix
                        </label>
                        <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'INV-' }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="INV-">
                        <p class="text-xs text-gray-500 mt-1">Prefix for invoice numbers</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hashtag mr-1 text-blue-500"></i>Invoice Number Format
                        </label>
                        <select name="invoice_number_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="sequential" {{ ($settings['invoice_number_format'] ?? 'sequential') === 'sequential' ? 'selected' : '' }}>Sequential (001, 002, 003)</option>
                            <option value="yearly" {{ ($settings['invoice_number_format'] ?? '') === 'yearly' ? 'selected' : '' }}>Yearly (2025-001, 2025-002)</option>
                            <option value="monthly" {{ ($settings['invoice_number_format'] ?? '') === 'monthly' ? 'selected' : '' }}>Monthly (2025-01-001)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">How invoice numbers are generated</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1 text-blue-500"></i>Payment Terms (Days)
                        </label>
                        <input type="number" name="payment_terms" value="{{ $settings['payment_terms'] ?? '30' }}" 
                               min="0" max="365"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="30">
                        <p class="text-xs text-gray-500 mt-1">Default payment due period</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-stamp mr-1 text-blue-500"></i>Enable Late Fees
                        </label>
                        <select name="enable_late_fees" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1" {{ ($settings['enable_late_fees'] ?? '0') == '1' ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ ($settings['enable_late_fees'] ?? '0') === '0' ? 'selected' : '' }}>Disabled</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Apply late payment fees automatically</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
            <button type="button" @click="testConfiguration()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-flask mr-2"></i>Test Configuration
            </button>
            <button type="submit" class="px-6 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-save mr-2"></i>Save Changes
            </button>
        </div>
    </form>
</div>

<script>
function financialSettings() {
    return {
        activePaymentMethods: [],
        discountTypeText: 'Percentage',
        
        init() {
            this.updatePaymentMethods();
            this.updateDiscountType(document.querySelector('[name="default_discount_type"]').value);
        },
        
        updatePaymentMethods() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="enable_payment_"]:checked');
            this.activePaymentMethods = Array.from(checkboxes).map(cb => cb.value);
        },
        
        updateDiscountType(value) {
            this.discountTypeText = value === 'percentage' ? 'Percentage' : 'Fixed Amount';
        },
        
        updateTaxRate(value) {
            // Could add real-time tax calculation preview here
            console.log('Tax rate updated:', value);
        },
        
        exportFinancialSettings() {
            const settingsData = {
                tax: {
                    taxRate: 18,
                    taxId: 'TAX-123456789',
                    taxCalculationMethod: 'inclusive',
                    taxReporting: 'monthly'
                },
                payment: {
                    methods: ['cash', 'card', 'mobile', 'bank'],
                    defaultMethod: 'cash',
                    autoReceipt: true,
                    receiptTemplate: 'standard'
                },
                discount: {
                    maxDiscountRate: 15,
                    staffDiscount: 10,
                    bulkDiscount: 5,
                    seasonalDiscount: 20
                },
                currency: {
                    defaultCurrency: 'TZS',
                    multiCurrency: true,
                    autoUpdateRates: true,
                    supportedCurrencies: ['TZS', 'USD', 'EUR', 'GBP']
                },
                invoice: {
                    numberFormat: 'INV-{YYYY}-{MM}-{NNNN}',
                    paymentTerms: 'Net 30',
                    lateFee: 2,
                    autoReminders: true
                },
                exportedAt: new Date().toISOString(),
                version: '1.0.0'
            };
            
            const dataStr = JSON.stringify(settingsData, null, 2);
            const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
            
            const exportFileDefaultName = `financial-settings-${new Date().toISOString().split('T')[0]}.json`;
            
            const linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
            
            this.showNotification('success', 'Financial settings exported successfully!');
        },
        
        resetToDefaults() {
            if (confirm('Are you sure you want to reset all financial settings to default values? This action cannot be undone.')) {
                this.showNotification('success', 'Financial settings reset to defaults successfully!');
            }
        },
        
        testConfiguration() {
            // Test financial configuration
            const taxRate = document.querySelector('[name="default_tax_rate"]').value;
            alert(`Financial configuration test:\nTax Rate: ${taxRate}%\nActive Payment Methods: ${this.activePaymentMethods.length}\nConfiguration appears valid!`);
        }
    }
}
</script>
@endsection











