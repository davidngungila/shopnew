@extends('layouts.app')

@section('title', $customer->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $customer->name }}</h1>
            <p class="text-gray-600 mt-1">Customer Details & Purchase History</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit</span>
            </a>
            <a href="{{ route('customers.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
                Back to List
            </a>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Name</label>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $customer->name }}</p>
                    </div>
                    @if($customer->email)
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Email</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $customer->email }}</p>
                    </div>
                    @endif
                    @if($customer->phone)
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Phone</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $customer->phone }}</p>
                    </div>
                    @endif
                    @if($customer->address)
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Address</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $customer->address }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Status</label>
                        <div class="mt-1">
                            @if($customer->is_active)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Total Purchases</label>
                        <p class="text-xl font-bold text-gray-900 mt-1">TZS {{ number_format($customer->total_purchases ?? 0, 0) }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Total Orders</label>
                        <p class="text-xl font-bold text-gray-900 mt-1">{{ $customer->sales->count() }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Loyalty Points</label>
                        <p class="text-xl font-bold text-green-600 mt-1">{{ number_format($customer->loyalty_points ?? 0, 0) }}</p>
                    </div>
                    @if($customer->last_purchase_date)
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Last Purchase</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $customer->last_purchase_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sales History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Purchase History</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($customer->sales as $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $sale->invoice_number }}</div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $sale->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-400">{{ $sale->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    @if($sale->status === 'completed')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                    @elseif($sale->status === 'pending')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @elseif($sale->status === 'cancelled')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Cancelled</span>
                                    @elseif($sale->status === 'refunded')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Refunded</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $sale->payment_method) }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                    TZS {{ number_format($sale->total, 0) }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('sales.show', $sale) }}" class="text-[#009245] hover:text-[#007a38]" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No purchases found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


