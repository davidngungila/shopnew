@extends('layouts.app')

@section('title', 'Manage Liabilities')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manage Liabilities</h1>
            <p class="text-gray-600 mt-1">Track loans, credits, and payables</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('liabilities.pdf', request()->query()) }}" target="_blank" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export PDF</span>
            </a>
            <a href="{{ route('liabilities.create') }}" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Add Liability</span>
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Liabilities</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600 mt-2">TSh {{ number_format($totalLiabilities ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Active Liabilities</p>
            <p class="text-xl sm:text-2xl font-bold text-orange-600 mt-2">TSh {{ number_format($activeLiabilities ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Overdue Liabilities</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600 mt-2">TSh {{ number_format($overdueLiabilities ?? 0, 0) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <form method="GET" action="{{ route('liabilities.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                <option value="">All Types</option>
                <option value="loan" {{ request('type') == 'loan' ? 'selected' : '' }}>Loan</option>
                <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                <option value="payable" {{ request('type') == 'payable' ? 'selected' : '' }}>Payable</option>
                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245]">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 text-white rounded-lg" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">Filter</button>
                <a href="{{ route('liabilities.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear</a>
            </div>
        </form>
    </div>

    <!-- Liabilities Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Liability #</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start / Due Date</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Principal (TZS)</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Outstanding (TZS)</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($liabilities ?? [] as $liability)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $liability->liability_number }}</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">{{ $liability->name }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($liability->type) }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($liability->start_date)->format('M d, Y') }}
                            @if($liability->due_date)
                            <br><span class="text-xs text-gray-400">Due: {{ \Carbon\Carbon::parse($liability->due_date)->format('M d, Y') }}</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">TSh {{ number_format($liability->principal_amount, 0) }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600 text-right">TSh {{ number_format($liability->outstanding_balance, 0) }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $liability->status === 'active' ? 'bg-blue-100 text-blue-800' : ($liability->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($liability->status) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('liabilities.show', $liability) }}" class="text-[#009245] hover:text-[#007a38]">View</a>
                                <a href="{{ route('liabilities.edit', $liability) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No liabilities found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($liabilities) && $liabilities->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">{{ $liabilities->links() }}</div>
        @endif
    </div>
</div>
@endsection
