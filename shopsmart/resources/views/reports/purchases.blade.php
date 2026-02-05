@extends('layouts.app')

@section('title', 'Purchase Reports')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Purchase Reports</h1>
            <p class="text-gray-600 mt-1">Track purchase orders and supplier analytics</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Export</button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <form method="GET" action="{{ route('reports.purchases') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <input type="date" name="date_from" value="{{ $dateFrom ?? request('date_from', now()->startOfMonth()->toDateString()) }}" class="px-4 py-2 border border-gray-300 rounded-lg">
            <input type="date" name="date_to" value="{{ $dateTo ?? request('date_to', now()->toDateString()) }}" class="px-4 py-2 border border-gray-300 rounded-lg">
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('reports.purchases') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Reset</a>
            </div>
        </form>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Purchases</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($totalPurchases ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Orders</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalOrders ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Average Order</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($averageOrder ?? 0, 0) }}</p>
        </div>
    </div>

    <!-- Purchases by Supplier -->
    @if(isset($purchasesBySupplier) && $purchasesBySupplier->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Purchases by Supplier</h2>
        <div class="space-y-3">
            @foreach($purchasesBySupplier as $supplier)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $supplier->supplier->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">{{ $supplier->count }} orders</p>
                </div>
                <span class="text-sm font-semibold text-gray-900">TZS {{ number_format($supplier->total, 0) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Purchases Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchases ?? [] as $purchase)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $purchase->purchase_number ?? $purchase->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $purchase->supplier->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $purchase->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">TZS {{ number_format($purchase->total, 0) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No purchases found for the selected period</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

