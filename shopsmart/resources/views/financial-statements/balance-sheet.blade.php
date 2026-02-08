@extends('layouts.app')

@section('title', 'Balance Sheet')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Balance Sheet</h1>
            <p class="text-gray-600 mt-1">Financial position statement</p>
        </div>
        <div class="flex gap-2">
            <input type="date" name="as_of_date" value="{{ $asOfDate ?? now()->toDateString() }}" onchange="window.location.href='{{ route('financial-statements.balance-sheet') }}?as_of_date='+this.value" class="px-4 py-2 border border-gray-300 rounded-lg">
            <button onclick="window.print()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Print</button>
        </div>
    </div>

    <!-- Balance Sheet -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assets -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Assets</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-700">Current Assets</span>
                    <span class="font-semibold text-gray-900">TZS {{ number_format($currentAssets ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-700">Fixed Assets</span>
                    <span class="font-semibold text-gray-900">TZS {{ number_format($fixedAssets ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-t-2 border-gray-300 mt-2">
                    <span class="text-lg font-bold text-gray-900">Total Assets</span>
                    <span class="text-lg font-bold text-gray-900">TZS {{ number_format($totalAssets ?? 0, 0) }}</span>
                </div>
            </div>
        </div>

        <!-- Liabilities & Equity -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Liabilities & Equity</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-700">Current Liabilities</span>
                    <span class="font-semibold text-gray-900">TZS {{ number_format($currentLiabilities ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-700">Long-term Liabilities</span>
                    <span class="font-semibold text-gray-900">TZS {{ number_format($longTermLiabilities ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b-2 border-gray-300">
                    <span class="font-semibold text-gray-900">Total Liabilities</span>
                    <span class="font-semibold text-gray-900">TZS {{ number_format($totalLiabilities ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b mt-2">
                    <span class="text-gray-700">Capital</span>
                    <span class="font-semibold text-gray-900">TZS {{ number_format($capital ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-700">Retained Earnings</span>
                    <span class="font-semibold text-gray-900">TZS {{ number_format($retainedEarnings ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-t-2 border-gray-300 mt-2">
                    <span class="text-lg font-bold text-gray-900">Total Equity</span>
                    <span class="text-lg font-bold text-gray-900">TZS {{ number_format($totalEquity ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-t-2 border-gray-300 mt-2">
                    <span class="text-xl font-bold text-gray-900">Total Liabilities & Equity</span>
                    <span class="text-xl font-bold text-gray-900">TZS {{ number_format(($totalLiabilities ?? 0) + ($totalEquity ?? 0), 0) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



