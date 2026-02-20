@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $supplier->name }}</h1>
            <p class="text-gray-600 mt-1">Supplier details and purchase history</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('suppliers.edit', $supplier) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Edit</a>
            <a href="{{ route('suppliers.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Back</a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Purchases</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalPurchases ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Value</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($totalPurchaseValue ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Paid</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600 mt-2">TZS {{ number_format($totalPaid ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Due</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600 mt-2">TZS {{ number_format($totalDue ?? 0, 0) }}</p>
        </div>
    </div>

    <!-- Supplier Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Supplier Information</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600">Contact Person</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ $supplier->contact_person ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Email</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ $supplier->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Phone</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ $supplier->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                @if($supplier->is_active)
                <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                @else
                <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                @endif
            </div>
            <div class="sm:col-span-2">
                <p class="text-sm text-gray-600">Address</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ $supplier->address ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Purchases -->
    @if($supplier->purchases->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Purchases</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($supplier->purchases as $purchase)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $purchase->purchase_number ?? $purchase->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $purchase->purchase_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">{{ $purchase->status }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">TZS {{ number_format($purchase->total, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection











