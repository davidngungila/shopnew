@extends('layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Chart of Accounts</h1>
            <p class="text-gray-600 mt-1">Manage your accounting chart of accounts</p>
        </div>
        <a href="{{ route('chart-of-accounts.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Add Account</span>
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Accounts</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalAccounts ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Balance</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($totalBalance ?? 0, 0) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <form method="GET" action="{{ route('chart-of-accounts.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search accounts..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <select name="account_type" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">All Types</option>
                @foreach($accountTypes ?? [] as $type)
                <option value="{{ $type }}" {{ request('account_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
            <select name="is_active" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">All Status</option>
                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('chart-of-accounts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear</a>
            </div>
        </form>
    </div>

    <!-- Accounts Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Name</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Balance</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($accounts ?? [] as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $account->account_code }}</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">{{ $account->account_name }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">{{ $account->account_type }}</span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">TZS {{ number_format($account->current_balance, 0) }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            @if($account->is_active)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('chart-of-accounts.show', $account) }}" class="text-purple-600 hover:text-purple-900">View</a>
                                <a href="{{ route('chart-of-accounts.edit', $account) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No accounts found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($accounts) && $accounts->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">{{ $accounts->links() }}</div>
        @endif
    </div>
</div>
@endsection

