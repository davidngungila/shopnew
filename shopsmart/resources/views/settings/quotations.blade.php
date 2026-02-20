@extends('layouts.app')

@section('title', 'Quotation Settings')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Quotation Settings</h1>
            <p class="text-gray-600 mt-1">Configure quotation defaults and templates</p>
        </div>
        <a href="{{ route('settings.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Back</a>
    </div>

    <form action="{{ route('settings.quotations.update') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Default Quotation Expiry (Days)</label>
                <input type="number" name="default_quotation_expiry_days" value="{{ $settings['default_quotation_expiry_days'] ?? '30' }}" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <p class="text-xs text-gray-500 mt-1">Default number of days before quotation expires</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quotation Number Prefix</label>
                <input type="text" name="quotation_number_prefix" value="{{ $settings['quotation_number_prefix'] ?? 'QUO-' }}" maxlength="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Default Terms & Conditions</label>
                <textarea name="default_terms_conditions" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter default terms and conditions that will be pre-filled when creating quotations...">{{ $settings['default_terms_conditions'] ?? '' }}</textarea>
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











