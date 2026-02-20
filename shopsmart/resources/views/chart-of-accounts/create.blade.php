@extends('layouts.app')

@section('title', 'Add Chart of Account')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Add Chart of Account</h1>
        <p class="text-gray-600 mt-1">Create a new account in your chart of accounts</p>
    </div>

    <form action="{{ route('chart-of-accounts.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="account_code" class="block text-sm font-medium text-gray-700 mb-2">Account Code *</label>
                <input 
                    type="text" 
                    name="account_code" 
                    id="account_code" 
                    value="{{ old('account_code') }}" 
                    required 
                    maxlength="50"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="e.g., 1000, 2000, 3000"
                >
                @error('account_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Unique code to identify this account</p>
            </div>

            <div>
                <label for="account_name" class="block text-sm font-medium text-gray-700 mb-2">Account Name *</label>
                <input 
                    type="text" 
                    name="account_name" 
                    id="account_name" 
                    value="{{ old('account_name') }}" 
                    required 
                    maxlength="255"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="e.g., Cash, Accounts Receivable, Sales Revenue"
                >
                @error('account_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="account_type" class="block text-sm font-medium text-gray-700 mb-2">Account Type *</label>
                <select 
                    name="account_type" 
                    id="account_type" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    onchange="updateAccountCategories()"
                >
                    <option value="">Select account type</option>
                    @foreach($accountTypes ?? [] as $type)
                        <option value="{{ $type }}" {{ old('account_type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
                @error('account_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="account_category" class="block text-sm font-medium text-gray-700 mb-2">Account Category</label>
                <select 
                    name="account_category" 
                    id="account_category" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                    <option value="">Select category (optional)</option>
                    <option value="current_asset" {{ old('account_category') == 'current_asset' ? 'selected' : '' }}>Current Asset</option>
                    <option value="fixed_asset" {{ old('account_category') == 'fixed_asset' ? 'selected' : '' }}>Fixed Asset</option>
                    <option value="current_liability" {{ old('account_category') == 'current_liability' ? 'selected' : '' }}>Current Liability</option>
                    <option value="long_term_liability" {{ old('account_category') == 'long_term_liability' ? 'selected' : '' }}>Long Term Liability</option>
                    <option value="equity" {{ old('account_category') == 'equity' ? 'selected' : '' }}>Equity</option>
                    <option value="revenue" {{ old('account_category') == 'revenue' ? 'selected' : '' }}>Revenue</option>
                    <option value="expense" {{ old('account_category') == 'expense' ? 'selected' : '' }}>Expense</option>
                    <option value="cost_of_sales" {{ old('account_category') == 'cost_of_sales' ? 'selected' : '' }}>Cost of Sales</option>
                </select>
                @error('account_category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="parent_account_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Account</label>
                <select 
                    name="parent_account_id" 
                    id="parent_account_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                    <option value="">No parent account (top level)</option>
                    @foreach($parentAccounts ?? [] as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_account_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->account_code }} - {{ $parent->account_name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_account_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Select a parent account if this is a sub-account</p>
            </div>

            <div>
                <label for="opening_balance" class="block text-sm font-medium text-gray-700 mb-2">Opening Balance (TZS)</label>
                <input 
                    type="number" 
                    name="opening_balance" 
                    id="opening_balance" 
                    value="{{ old('opening_balance', 0) }}" 
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="0.00"
                >
                @error('opening_balance')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                <input 
                    type="number" 
                    name="sort_order" 
                    id="sort_order" 
                    value="{{ old('sort_order', 0) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="0"
                >
                @error('sort_order')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
            </div>

            <div>
                <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="flex items-center space-x-3 mt-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-[#009245] border-gray-300 rounded focus:ring-[#009245]">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
                @error('is_active')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="Enter account description..."
                >{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('chart-of-accounts.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-white rounded-lg font-semibold transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                Create Account
            </button>
        </div>
    </form>
</div>

<script>
function updateAccountCategories() {
    const accountType = document.getElementById('account_type').value;
    const categorySelect = document.getElementById('account_category');
    const currentValue = categorySelect.value;
    
    // Clear existing options except the first one
    categorySelect.innerHTML = '<option value="">Select category (optional)</option>';
    
    // Add categories based on account type
    const categories = {
        'asset': [
            {value: 'current_asset', label: 'Current Asset'},
            {value: 'fixed_asset', label: 'Fixed Asset'}
        ],
        'liability': [
            {value: 'current_liability', label: 'Current Liability'},
            {value: 'long_term_liability', label: 'Long Term Liability'}
        ],
        'equity': [
            {value: 'equity', label: 'Equity'}
        ],
        'revenue': [
            {value: 'revenue', label: 'Revenue'}
        ],
        'expense': [
            {value: 'expense', label: 'Expense'},
            {value: 'cost_of_sales', label: 'Cost of Sales'}
        ]
    };
    
    if (categories[accountType]) {
        categories[accountType].forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.value;
            option.textContent = cat.label;
            if (currentValue === cat.value) {
                option.selected = true;
            }
            categorySelect.appendChild(option);
        });
    } else {
        // If no specific categories, show all
        const allCategories = [
            {value: 'current_asset', label: 'Current Asset'},
            {value: 'fixed_asset', label: 'Fixed Asset'},
            {value: 'current_liability', label: 'Current Liability'},
            {value: 'long_term_liability', label: 'Long Term Liability'},
            {value: 'equity', label: 'Equity'},
            {value: 'revenue', label: 'Revenue'},
            {value: 'expense', label: 'Expense'},
            {value: 'cost_of_sales', label: 'Cost of Sales'}
        ];
        allCategories.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.value;
            option.textContent = cat.label;
            if (currentValue === cat.value) {
                option.selected = true;
            }
            categorySelect.appendChild(option);
        });
    }
}

// Initialize categories on page load
document.addEventListener('DOMContentLoaded', function() {
    updateAccountCategories();
});
</script>
@endsection








