@extends('layouts.app')

@section('title', 'Sales Reports')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Sales Reports</h1>
            <p class="text-gray-600 mt-1">Comprehensive sales analytics and insights</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <span>Export</span>
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <form method="GET" action="{{ route('reports.sales') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <input type="date" name="date_from" value="{{ $dateFrom ?? request('date_from', now()->startOfMonth()->toDateString()) }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <input type="date" name="date_to" value="{{ $dateTo ?? request('date_to', now()->toDateString()) }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('reports.sales') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Reset</a>
            </div>
        </form>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Sales</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($totalSales ?? 0, 0) }}</p>
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

    <!-- Sales by Payment Method -->
    @if(isset($salesByPayment) && $salesByPayment->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Sales by Payment Method</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($salesByPayment as $method)
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 capitalize">{{ $method->payment_method ?? 'N/A' }}</p>
                <p class="text-xl font-bold text-gray-900 mt-1">TZS {{ number_format($method->total, 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $method->count }} orders</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Daily Sales Chart -->
    @if(isset($dailySales) && $dailySales->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Daily Sales Trend</h2>
        <div style="position: relative; height: 300px;">
            <canvas id="dailySalesChart"></canvas>
        </div>
    </div>
    @endif

    <!-- Sales Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales ?? [] as $sale)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $sale->invoice_number ?? $sale->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sale->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">TZS {{ number_format($sale->total, 0) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No sales found for the selected period</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dailySalesData = @json($dailySales ?? []);
        const ctx = document.getElementById('dailySalesChart');
        
        if (!ctx || !dailySalesData || dailySalesData.length === 0) {
            return;
        }

        if (typeof Chart === 'undefined') {
            return;
        }

        try {
            const labels = dailySalesData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            });
            const totals = dailySalesData.map(item => parseFloat(item.total || 0));

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales (TZS)',
                        data: totals,
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        borderWidth: 2,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Sales: TZS ' + context.parsed.y.toLocaleString('en-US');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'TZS ' + (value / 1000000).toFixed(1) + 'M';
                                    } else if (value >= 1000) {
                                        return 'TZS ' + (value / 1000).toFixed(0) + 'K';
                                    }
                                    return 'TZS ' + value.toLocaleString('en-US');
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating chart:', error);
        }
    });
</script>
@endsection
@endsection

