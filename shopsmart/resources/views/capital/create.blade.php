@extends('layouts.app')

@section('title', 'Add Capital Transaction')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Add Capital Transaction</h1>
        <p class="text-gray-600 mt-1">Record capital contribution, withdrawal, profit, or loss</p>
    </div>

    <form action="{{ route('capital.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Transaction Type *</label>
                <select 
                    name="type" 
                    id="type" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                    <option value="">Select type</option>
                    <option value="contribution" {{ old('type') == 'contribution' ? 'selected' : '' }}>Contribution</option>
                    <option value="withdrawal" {{ old('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                    <option value="profit" {{ old('type') == 'profit' ? 'selected' : '' }}>Profit</option>
                    <option value="loss" {{ old('type') == 'loss' ? 'selected' : '' }}>Loss</option>
                </select>
                @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (TZS) *</label>
                <input 
                    type="number" 
                    name="amount" 
                    id="amount" 
                    value="{{ old('amount') }}" 
                    required 
                    min="0" 
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="0.00"
                >
                @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">Transaction Date *</label>
                <input 
                    type="date" 
                    name="transaction_date" 
                    id="transaction_date" 
                    value="{{ old('transaction_date', date('Y-m-d')) }}" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                @error('transaction_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="account_id" class="block text-sm font-medium text-gray-700 mb-2">Account</label>
                <select 
                    name="account_id" 
                    id="account_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                    <option value="">Select account (optional)</option>
                    @foreach($accounts ?? [] as $account)
                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->account_name }} ({{ $account->account_code }})
                        </option>
                    @endforeach
                </select>
                @error('account_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="reference" class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                <input 
                    type="text" 
                    name="reference" 
                    id="reference" 
                    value="{{ old('reference') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="e.g., Receipt #12345, Invoice #INV-001"
                >
                @error('reference')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="Enter transaction description or notes..."
                >{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('capital.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-white rounded-lg font-semibold transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                Record Transaction
            </button>
        </div>
    </form>
</div>
@endsection








