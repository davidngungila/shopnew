@extends('layouts.app')

@section('title', 'Account Details: ' . $chartOfAccount->account_name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Account Details</h1>
            <p class="text-gray-600 mt-1">{{ $chartOfAccount->account_name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('chart-of-accounts.edit', $chartOfAccount) }}" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit Account</span>
            </a>
            <a href="{{ route('chart-of-accounts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Account Code</dt>
                        <dd class="mt-1 text-lg text-gray-900 font-semibold font-mono">{{ $chartOfAccount->account_code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Account Name</dt>
                        <dd class="mt-1 text-lg text-gray-900 font-semibold">{{ $chartOfAccount->account_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Account Type</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                {{ $chartOfAccount->account_type }}
                            </span>
                        </dd>
                    </div>
                    @if($chartOfAccount->account_category)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Account Category</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $chartOfAccount->account_category) }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Current Balance</dt>
                        <dd class="mt-1 text-lg text-gray-900 font-bold" style="color: {{ $chartOfAccount->current_balance >= 0 ? '#059669' : '#dc2626' }};">
                            TZS {{ number_format($chartOfAccount->current_balance ?? 0, 0) }}
                        </dd>
                    </div>
                    @if($chartOfAccount->opening_balance)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Opening Balance</dt>
                        <dd class="mt-1 text-sm text-gray-900">TZS {{ number_format($chartOfAccount->opening_balance, 0) }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $chartOfAccount->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $chartOfAccount->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                    @if($chartOfAccount->parentAccount)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Parent Account</dt>
                        <dd class="mt-1">
                            <a href="{{ route('chart-of-accounts.show', $chartOfAccount->parentAccount) }}" class="text-sm text-[#009245] hover:text-[#007a38] font-semibold">
                                {{ $chartOfAccount->parentAccount->account_name }} ({{ $chartOfAccount->parentAccount->account_code }})
                            </a>
                        </dd>
                    </div>
                    @endif
                    @if($chartOfAccount->description)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $chartOfAccount->description }}</dd>
                    </div>
                    @endif
                    @if($chartOfAccount->sort_order)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sort Order</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $chartOfAccount->sort_order }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $chartOfAccount->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $chartOfAccount->updated_at->format('M d, Y h:i A') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Child Accounts -->
            @if($chartOfAccount->childAccounts && $chartOfAccount->childAccounts->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Child Accounts ({{ $chartOfAccount->childAccounts->count() }})</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($chartOfAccount->childAccounts as $child)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ $child->account_code }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $child->account_name }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $child->account_type }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-right font-semibold" style="color: {{ $child->current_balance >= 0 ? '#059669' : '#dc2626' }};">
                                    TZS {{ number_format($child->current_balance ?? 0, 0) }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $child->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $child->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('chart-of-accounts.show', $child) }}" class="text-[#009245] hover:text-[#007a38]" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('chart-of-accounts.edit', $chartOfAccount) }}" class="block w-full px-4 py-2 text-center text-white rounded-lg" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                        Edit Account
                    </a>
                    <a href="{{ route('chart-of-accounts.index') }}" class="block w-full px-4 py-2 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Account Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Summary</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Account Type</dt>
                        <dd class="text-sm font-semibold text-gray-900 capitalize">{{ $chartOfAccount->account_type }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Current Balance</dt>
                        <dd class="text-sm font-bold" style="color: {{ $chartOfAccount->current_balance >= 0 ? '#059669' : '#dc2626' }};">
                            TZS {{ number_format($chartOfAccount->current_balance ?? 0, 0) }}
                        </dd>
                    </div>
                    @if($chartOfAccount->childAccounts && $chartOfAccount->childAccounts->count() > 0)
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Child Accounts</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $chartOfAccount->childAccounts->count() }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $chartOfAccount->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $chartOfAccount->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection








