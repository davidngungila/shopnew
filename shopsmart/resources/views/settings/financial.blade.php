@extends('layouts.app')

@section('title', 'Financial Settings')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Financial Settings</h1>
            <p class="text-gray-600 mt-1">Configure tax rates and payment methods</p>
        </div>
        <a href="{{ route('settings.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Back</a>
    </div>

    <form action="{{ route('settings.financial.update') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @csrf
        
        <div class="space-y-6">
            <!-- Tax Settings -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Tax Settings</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Tax Rate (%)</label>
                        <input type="number" name="default_tax_rate" value="{{ $settings['default_tax_rate'] ?? '10' }}" step="0.01" min="0" max="100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Discount Type</label>
                        <select name="default_discount_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="percentage" {{ ($settings['default_discount_type'] ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed" {{ ($settings['default_discount_type'] ?? '') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Methods</h2>
                <div class="space-y-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="enable_payment_cash" value="1" {{ ($settings['enable_payment_cash'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">Cash</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="enable_payment_card" value="1" {{ ($settings['enable_payment_card'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">Card / Credit Card</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="enable_payment_mobile_money" value="1" {{ ($settings['enable_payment_mobile_money'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">Mobile Money</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="enable_payment_bank" value="1" {{ ($settings['enable_payment_bank'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">Bank Transfer</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold">
                    Save Settings
                </button>
            </div>
        </div>
    </form>
</div>
@endsection



