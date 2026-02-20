@extends('layouts.app')

@section('title', 'New Bank Reconciliation')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">New Bank Reconciliation</h1>
        <p class="text-gray-600 mt-1">Reconcile bank statement with book balance</p>
    </div>

    <form action="{{ route('bank-reconciliations.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="sm:col-span-2">
                <label for="account_id" class="block text-sm font-medium text-gray-700 mb-2">Bank Account *</label>
                <select 
                    name="account_id" 
                    id="account_id" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                    <option value="">Select bank account</option>
                    @foreach($accounts ?? [] as $account)
                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->account_name }} ({{ $account->account_code }})
                        </option>
                    @endforeach
                </select>
                @error('account_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="statement_date" class="block text-sm font-medium text-gray-700 mb-2">Statement Date *</label>
                <input 
                    type="date" 
                    name="statement_date" 
                    id="statement_date" 
                    value="{{ old('statement_date', date('Y-m-d')) }}" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                @error('statement_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="bank_balance" class="block text-sm font-medium text-gray-700 mb-2">Bank Balance (TZS) *</label>
                <input 
                    type="number" 
                    name="bank_balance" 
                    id="bank_balance" 
                    value="{{ old('bank_balance') }}" 
                    required 
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="0.00"
                >
                @error('bank_balance')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Balance from bank statement</p>
            </div>

            <div>
                <label for="book_balance" class="block text-sm font-medium text-gray-700 mb-2">Book Balance (TZS) *</label>
                <input 
                    type="number" 
                    name="book_balance" 
                    id="book_balance" 
                    value="{{ old('book_balance') }}" 
                    required 
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="0.00"
                >
                @error('book_balance')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Balance from accounting records</p>
            </div>

            <div>
                <label for="deposits_in_transit" class="block text-sm font-medium text-gray-700 mb-2">Deposits in Transit (TZS)</label>
                <input 
                    type="number" 
                    name="deposits_in_transit" 
                    id="deposits_in_transit" 
                    value="{{ old('deposits_in_transit', 0) }}" 
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="0.00"
                >
                @error('deposits_in_transit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Deposits recorded but not yet on statement</p>
            </div>

            <div>
                <label for="outstanding_checks" class="block text-sm font-medium text-gray-700 mb-2">Outstanding Checks (TZS)</label>
                <input 
                    type="number" 
                    name="outstanding_checks" 
                    id="outstanding_checks" 
                    value="{{ old('outstanding_checks', 0) }}" 
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="0.00"
                >
                @error('outstanding_checks')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Checks issued but not yet cleared</p>
            </div>

            <div>
                <label for="bank_charges" class="block text-sm font-medium text-gray-700 mb-2">Bank Charges (TZS)</label>
                <input 
                    type="number" 
                    name="bank_charges" 
                    id="bank_charges" 
                    value="{{ old('bank_charges', 0) }}" 
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="0.00"
                >
                @error('bank_charges')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Fees charged by bank</p>
            </div>

            <div>
                <label for="interest_earned" class="block text-sm font-medium text-gray-700 mb-2">Interest Earned (TZS)</label>
                <input 
                    type="number" 
                    name="interest_earned" 
                    id="interest_earned" 
                    value="{{ old('interest_earned', 0) }}" 
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="0.00"
                >
                @error('interest_earned')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Interest earned on account</p>
            </div>

            <div class="sm:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea 
                    name="notes" 
                    id="notes" 
                    rows="4" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="Enter any additional notes or discrepancies..."
                >{{ old('notes') }}</textarea>
                @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('bank-reconciliations.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-white rounded-lg font-semibold transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                Create Reconciliation
            </button>
        </div>
    </form>
</div>
@endsection








