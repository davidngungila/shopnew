@extends('layouts.app')

@section('title', 'Bank Reconciliation')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Bank Reconciliation</h1>
            <p class="text-gray-600 mt-1">Reconcile bank statements with book balances</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('bank-reconciliations.pdf', request()->query()) }}" target="_blank" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export PDF</span>
            </a>
            <a href="{{ route('bank-reconciliations.create') }}" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Reconciliation</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <form method="GET" action="{{ route('bank-reconciliations.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <select name="account_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                <option value="">All Accounts</option>
                @foreach($accounts ?? [] as $account)
                <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reconciled" {{ request('status') == 'reconciled' ? 'selected' : '' }}>Reconciled</option>
                <option value="discrepancy" {{ request('status') == 'discrepancy' ? 'selected' : '' }}>Discrepancy</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">Filter</button>
                <a href="{{ route('bank-reconciliations.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear</a>
            </div>
        </form>
    </div>

    <!-- Reconciliations Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reconciliation #</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statement Date</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Bank Balance (TSh)</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Book Balance (TSh)</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Adjusted Balance (TSh)</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reconciliations ?? [] as $reconciliation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $reconciliation->reconciliation_number }}</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">{{ $reconciliation->account->account_name ?? '-' }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($reconciliation->statement_date)->format('M d, Y') }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">TSh {{ number_format($reconciliation->bank_balance, 0) }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">TSh {{ number_format($reconciliation->book_balance, 0) }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">TSh {{ number_format($reconciliation->adjusted_balance, 0) }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $reconciliation->status === 'reconciled' ? 'bg-green-100 text-green-800' : ($reconciliation->status === 'discrepancy' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($reconciliation->status) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('bank-reconciliations.show', $reconciliation) }}" class="text-[#009245] hover:text-[#007a38]">View</a>
                                <a href="{{ route('bank-reconciliations.edit', $reconciliation) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No reconciliations found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($reconciliations) && $reconciliations->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">{{ $reconciliations->links() }}</div>
        @endif
    </div>
</div>
@endsection
