@extends('layouts.app')

@section('title', 'Profit & Loss Statement')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Profit & Loss Statement</h1>
            <p class="text-gray-600 mt-1">Financial performance analysis</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('financial-statements.profit-loss.pdf', request()->query()) }}" target="_blank" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export PDF</span>
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Print</button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <form method="GET" action="{{ route('financial-statements.profit-loss') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <input type="date" name="date_from" value="{{ $dateFrom ?? request('date_from', now()->startOfMonth()->toDateString()) }}" class="px-4 py-2 border border-gray-300 rounded-lg">
            <input type="date" name="date_to" value="{{ $dateTo ?? request('date_to', now()->toDateString()) }}" class="px-4 py-2 border border-gray-300 rounded-lg">
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('financial-statements.profit-loss') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Reset</a>
            </div>
        </form>
    </div>

    <!-- P&L Statement -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Profit & Loss Statement</h2>
        <div class="space-y-4">
            <!-- Revenue -->
            <div class="flex justify-between items-center py-3 border-b">
                <span class="text-lg font-semibold text-gray-900">Revenue</span>
                <span class="text-lg font-semibold text-gray-900">TSh {{ number_format($revenue ?? 0, 0) }}</span>
            </div>

            <!-- Cost of Goods Sold -->
            <div class="flex justify-between items-center py-3 border-b">
                <span class="text-gray-700 ml-4">Cost of Goods Sold</span>
                <span class="text-gray-700">TSh {{ number_format($cogs ?? 0, 0) }}</span>
            </div>

            <!-- Gross Profit -->
            <div class="flex justify-between items-center py-3 border-b-2 border-gray-300">
                <span class="text-lg font-semibold text-gray-900">Gross Profit</span>
                <span class="text-lg font-semibold {{ ($grossProfit ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    TSh {{ number_format($grossProfit ?? 0, 0) }}
                </span>
            </div>

            <!-- Operating Expenses -->
            <div class="mt-4">
                <h3 class="text-md font-semibold text-gray-900 mb-2">Operating Expenses</h3>
                @if(isset($expenseBreakdown) && $expenseBreakdown->count() > 0)
                    @foreach($expenseBreakdown as $expense)
                    <div class="flex justify-between items-center py-2 ml-4">
                        <span class="text-gray-700">{{ $expense->category }}</span>
                        <span class="text-gray-700">TSh {{ number_format($expense->total, 0) }}</span>
                    </div>
                    @endforeach
                @endif
                <div class="flex justify-between items-center py-3 border-b mt-2">
                    <span class="font-semibold text-gray-900">Total Operating Expenses</span>
                    <span class="font-semibold text-gray-900">TSh {{ number_format($operatingExpenses ?? 0, 0) }}</span>
                </div>
            </div>

            <!-- Net Profit -->
            <div class="flex justify-between items-center py-4 border-t-2 border-gray-300 mt-4">
                <span class="text-xl font-bold text-gray-900">Net Profit / Loss</span>
                <span class="text-xl font-bold {{ ($netProfit ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    TSh {{ number_format($netProfit ?? 0, 0) }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection




