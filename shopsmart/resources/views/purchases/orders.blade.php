@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Purchase Orders</h1>
            <p class="text-gray-600 mt-1">Track and manage all purchase orders</p>
        </div>
        <a href="{{ route('purchases.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>New Order</span>
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Orders</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalOrders ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Value</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                        TZS {{ number_format($totalValue ?? 0, 0) }}
                    </p>
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
                    <p class="text-xs sm:text-sm text-gray-600">Pending Orders</p>
                    <p class="text-xl sm:text-2xl font-bold text-yellow-600 mt-2">{{ number_format($pendingOrders ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Overdue Orders</p>
                    <p class="text-xl sm:text-2xl font-bold text-red-600 mt-2">{{ number_format($overdueOrders ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Filters & Search</h2>
            <div class="flex gap-2">
                <button onclick="setDateRange('today')" class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Today</button>
                <button onclick="setDateRange('week')" class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">This Week</button>
                <button onclick="setDateRange('month')" class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">This Month</button>
                <button onclick="setDateRange('year')" class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">This Year</button>
            </div>
        </div>
        <form method="GET" action="{{ route('purchases.orders') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order, supplier..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <select name="supplier_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers ?? [] as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>

                <input type="date" name="date_from" id="date_from" value="{{ request('date_from', $dateFrom ?? '') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to', $dateTo ?? '') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Apply Filters</button>
                <a href="{{ route('purchases.orders') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear All</a>
            </div>
        </form>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Orders Trend Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Orders Trend (Last 30 Days)</h2>
            <canvas id="ordersTrendChart" height="100"></canvas>
        </div>

        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Distribution</h2>
            <canvas id="statusChart" height="100"></canvas>
        </div>
    </div>

    <!-- Top Suppliers -->
    @if(isset($topSuppliers) && $topSuppliers->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Suppliers by Order Value</h2>
        <div class="space-y-3">
            @foreach($topSuppliers as $index => $supplierData)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $supplierData->supplier->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500">{{ $supplierData->order_count }} orders</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900">TZS {{ number_format($supplierData->total_value, 0) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Status Breakdown -->
    @if(isset($statusBreakdown) && $statusBreakdown->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Breakdown</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($statusBreakdown as $status)
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 capitalize">{{ $status->status ?? 'N/A' }}</p>
                <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($status->count) }}</p>
                <p class="text-xs text-gray-500 mt-1">TZS {{ number_format($status->total, 0) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expected Delivery</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchases ?? [] as $purchase)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $purchase->purchase_number ?? $purchase->id }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $purchase->supplier->name ?? 'N/A' }}</div>
                            @if($purchase->supplier)
                            <div class="text-xs text-gray-500">{{ $purchase->supplier->phone ?? '' }}</div>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $purchase->items->count() ?? 0 }} items</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'ordered' => 'bg-blue-100 text-blue-800',
                                    'partial' => 'bg-orange-100 text-orange-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $color = $statusColors[$purchase->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize">
                                {{ $purchase->status ?? 'pending' }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($purchase->expected_delivery_date)
                                {{ $purchase->expected_delivery_date->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}
                                @if($purchase->expected_delivery_date < now() && in_array($purchase->status, ['pending', 'ordered', 'partial']))
                                    <div class="text-xs text-red-600">Overdue</div>
                                @endif
                            @else
                                <span class="text-gray-400">Not set</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                            TZS {{ number_format($purchase->total, 0) }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('purchases.show', $purchase) }}" class="text-purple-600 hover:text-purple-900" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No purchase orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($purchases) && $purchases->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
            {{ $purchases->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Orders Trend Chart
    @if(isset($dailyOrders) && $dailyOrders->count() > 0)
    const ordersTrendCtx = document.getElementById('ordersTrendChart');
    if (ordersTrendCtx) {
        const ordersData = @json($dailyOrders);
        const labels = ordersData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const totals = ordersData.map(item => parseFloat(item.total || 0));
        const counts = ordersData.map(item => parseInt(item.count || 0));

        new Chart(ordersTrendCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Amount (TZS)',
                    data: totals,
                    borderColor: 'rgb(147, 51, 234)',
                    backgroundColor: 'rgba(147, 51, 234, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y',
                }, {
                    label: 'Order Count',
                    data: counts,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'TZS ' + new Intl.NumberFormat().format(value);
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }
    @endif

    // Status Distribution Chart
    @if(isset($statusBreakdown) && $statusBreakdown->count() > 0)
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        const statusData = @json($statusBreakdown);
        const statusLabels = statusData.map(item => {
            return (item.status || 'Unknown').replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        });
        const statusTotals = statusData.map(item => parseFloat(item.total || 0));
        const colors = ['rgb(234, 179, 8)', 'rgb(59, 130, 246)', 'rgb(249, 115, 22)', 'rgb(34, 197, 94)', 'rgb(239, 68, 68)'];

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusTotals,
                    backgroundColor: colors.slice(0, statusLabels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'TZS ' + new Intl.NumberFormat().format(context.parsed);
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
    @endif

    // Date Range Presets
    function setDateRange(range) {
        const today = new Date();
        let from, to;
        
        switch(range) {
            case 'today':
                from = to = today.toISOString().split('T')[0];
                break;
            case 'week':
                const weekStart = new Date(today);
                weekStart.setDate(today.getDate() - today.getDay());
                from = weekStart.toISOString().split('T')[0];
                to = today.toISOString().split('T')[0];
                break;
            case 'month':
                from = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                to = today.toISOString().split('T')[0];
                break;
            case 'year':
                from = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
                to = today.toISOString().split('T')[0];
                break;
        }
        
        document.getElementById('date_from').value = from;
        document.getElementById('date_to').value = to;
    }
</script>
@endsection
