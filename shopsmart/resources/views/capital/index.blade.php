@extends('layouts.app')

@section('title', 'Manage Capital')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manage Capital</h1>
            <p class="text-gray-600 mt-1">Track capital contributions and withdrawals</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('capital.pdf', request()->query()) }}" target="_blank" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export PDF</span>
            </a>
            <a href="{{ route('capital.create') }}" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Add Transaction</span>
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Contributions</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600 mt-2">TSh {{ number_format($totalContributions ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Withdrawals</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600 mt-2">TSh {{ number_format($totalWithdrawals ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Net Capital</p>
            <p class="text-xl sm:text-2xl font-bold {{ ($netCapital ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">TSh {{ number_format($netCapital ?? 0, 0) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <form method="GET" action="{{ route('capital.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                <option value="">All Types</option>
                <option value="contribution" {{ request('type') == 'contribution' ? 'selected' : '' }}>Contribution</option>
                <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                <option value="profit" {{ request('type') == 'profit' ? 'selected' : '' }}>Profit</option>
                <option value="loss" {{ request('type') == 'loss' ? 'selected' : '' }}>Loss</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">Filter</button>
                <a href="{{ route('capital.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear</a>
            </div>
        </form>
    </div>

    <!-- Capital Transactions Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction #</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount (TZS)</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($capitals ?? [] as $capital)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $capital->transaction_number }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($capital->transaction_date)->format('M d, Y') }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $capital->type === 'contribution' || $capital->type === 'profit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($capital->type) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-500">{{ Str::limit($capital->description ?? '-', 40) }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $capital->reference ?? '-' }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-right {{ $capital->type === 'contribution' || $capital->type === 'profit' ? 'text-green-600' : 'text-red-600' }}">
                            TSh {{ number_format($capital->amount, 0) }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('capital.show', $capital) }}" class="text-[#009245] hover:text-[#007a38]">View</a>
                                <a href="{{ route('capital.edit', $capital) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No capital transactions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($capitals) && $capitals->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">{{ $capitals->links() }}</div>
        @endif
    </div>
</div>
@endsection
