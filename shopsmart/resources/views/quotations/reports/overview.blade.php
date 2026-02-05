@extends('layouts.app')

@section('title', 'Quotation Reports')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Quotation Reports</h1>
            <p class="text-gray-600 mt-1">Advanced analytics and insights for quotations</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <span>Export</span>
        </button>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Quotations</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalQuotations ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Value</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($totalValue ?? 0, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Conversion Rate</p>
                    <p class="text-xl sm:text-2xl font-bold text-blue-600 mt-2">{{ number_format($conversionRate ?? 0, 1) }}%</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($convertedQuotations ?? 0) }} converted</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Average Value</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($averageQuotationValue ?? 0, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Pending Value</p>
            <p class="text-xl sm:text-2xl font-bold text-yellow-600 mt-2">TZS {{ number_format($pendingValue ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Approved Value</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600 mt-2">TZS {{ number_format($approvedValue ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Converted Value</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-600 mt-2">TZS {{ number_format($convertedValue ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Approval Rate</p>
            <p class="text-xl sm:text-2xl font-bold text-purple-600 mt-2">{{ number_format($approvalRate ?? 0, 1) }}%</p>
        </div>
    </div>

    <!-- Monthly Trend Chart -->
    @if(isset($monthlyStats) && $monthlyStats->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Monthly Trend (Last 12 Months)</h2>
        <div style="position: relative; height: 300px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
    @endif

    <!-- Status Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Breakdown</h2>
            <div class="space-y-3">
                @if(isset($statusBreakdown))
                    @foreach($statusBreakdown as $status)
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'converted' => 'bg-blue-100 text-blue-800',
                            'expired' => 'bg-gray-100 text-gray-800',
                        ];
                        $color = $statusColors[$status->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize">{{ $status->status ?? 'N/A' }}</span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ number_format($status->count) }}</p>
                            <p class="text-xs text-gray-500">TZS {{ number_format($status->total, 0) }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                            <span class="text-sm text-gray-700">Pending</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $pendingQuotations ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-green-500 rounded"></div>
                            <span class="text-sm text-gray-700">Approved</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $approvedQuotations ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-red-500 rounded"></div>
                            <span class="text-sm text-gray-700">Rejected</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $rejectedQuotations ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-blue-500 rounded"></div>
                            <span class="text-sm text-gray-700">Converted</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $convertedQuotations ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-gray-500 rounded"></div>
                            <span class="text-sm text-gray-700">Expired</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $expiredQuotations ?? 0 }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Customers -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Customers by Quotation Value</h2>
            <div class="space-y-3">
                @forelse($topCustomers ?? [] as $customer)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $customer->name }}</p>
                        <p class="text-xs text-gray-500">{{ $customer->count }} quotation(s) • {{ number_format($customer->converted_count ?? 0) }} converted</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">TZS {{ number_format($customer->total, 0) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500">No data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Quotations -->
    @if(isset($recentQuotations) && $recentQuotations->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Quotations</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quotation #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentQuotations as $quotation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $quotation->quotation_number ?? $quotation->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $quotation->customer->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $quotation->quotation_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'converted' => 'bg-blue-100 text-blue-800',
                                    'expired' => 'bg-gray-100 text-gray-800',
                                ];
                                $color = $statusColors[$quotation->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize">{{ $quotation->status }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">TZS {{ number_format($quotation->total, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyStatsData = @json($monthlyStats ?? []);
        const ctx = document.getElementById('monthlyChart');
        
        if (!ctx || !monthlyStatsData || monthlyStatsData.length === 0) {
            return;
        }

        if (typeof Chart === 'undefined') {
            return;
        }

        try {
            const labels = monthlyStatsData.map(item => {
                const [year, month] = item.month.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
            });
            
            const counts = monthlyStatsData.map(item => parseInt(item.count || 0));
            const totals = monthlyStatsData.map(item => parseFloat(item.total || 0));
            const convertedCounts = monthlyStatsData.map(item => parseInt(item.converted_count || 0));

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Quotation Count',
                        data: counts,
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        yAxisID: 'y',
                    }, {
                        label: 'Quotation Value (TZS)',
                        data: totals,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        yAxisID: 'y1',
                    }, {
                        label: 'Converted Count',
                        data: convertedCounts,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: false,
                        yAxisID: 'y',
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
                                    if (context.datasetIndex === 1) {
                                        return 'Value: TZS ' + context.parsed.y.toLocaleString('en-US');
                                    }
                                    return context.dataset.label + ': ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false,
                            },
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
