@extends('layouts.app')

@section('title', 'All Purchases')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">All Purchases</h1>
            <p class="text-gray-600 mt-1">Advanced purchase management and analytics</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('purchases.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Purchase</span>
            </a>
            <button onclick="exportToPDF()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export PDF</span>
            </button>
        </div>
    </div>

    <!-- Advanced Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Total Purchases -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Purchases</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalPurchases ?? 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">All time</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Amount -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Amount</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                        TZS {{ number_format($totalAmount ?? 0, 0) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">All time</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">This Month</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                        TZS {{ number_format($thisMonthAmount ?? 0, 0) }}
                    </p>
                    <div class="flex items-center mt-2">
                        @php
                            $growth = $monthGrowth ?? 0;
                            $growth = is_numeric($growth) && is_finite($growth) ? $growth : 0;
                        @endphp
                        @if($growth >= 0)
                        <span class="text-xs text-green-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            {{ number_format(abs($growth), 1) }}%
                        </span>
                        @else
                        <span class="text-xs text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6 6"></path>
                            </svg>
                            {{ number_format(abs($growth), 1) }}%
                        </span>
                        @endif
                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Purchases -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Today's Purchases</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ number_format($todayPurchases ?? 0) }} orders</p>
                    <p class="text-xs text-gray-500 mt-1">TZS {{ number_format($todayAmount ?? 0, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtered Statistics -->
    @if(request()->hasAny(['date_from', 'date_to', 'status', 'supplier_id', 'search']))
    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg border border-purple-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filtered Results</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600">Filtered Purchases</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($filteredPurchases ?? 0) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Filtered Amount</p>
                <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($filteredAmount ?? 0, 0) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Average Purchase</p>
                <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($averagePurchase ?? 0, 0) }}</p>
            </div>
        </div>
    </div>
    @endif

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
        <form method="GET" action="{{ route('purchases.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search purchase #, supplier..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                
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
                <a href="{{ route('purchases.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear All</a>
            </div>
        </form>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Purchases Trend Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Purchase Trend (Last 30 Days)</h2>
            <canvas id="purchasesTrendChart" height="100"></canvas>
        </div>

        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Distribution</h2>
            <canvas id="statusChart" height="100"></canvas>
        </div>
    </div>

    <!-- Analytics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Suppliers -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Suppliers</h2>
            @if(isset($topSuppliers) && $topSuppliers->count() > 0)
            <div class="space-y-3">
                @foreach($topSuppliers as $index => $supplierData)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $supplierData->supplier->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $supplierData->purchase_count }} purchases</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">TZS {{ number_format($supplierData->total_spent, 0) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-8">No supplier data available</p>
            @endif
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Purchased Products</h2>
            @if(isset($topProducts) && $topProducts->count() > 0)
            <div class="space-y-3">
                @foreach($topProducts as $index => $productData)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $productData->product->name ?? 'Unknown Product' }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($productData->total_quantity) }} units</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">TZS {{ number_format($productData->total_cost, 0) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-8">No product data available</p>
            @endif
        </div>
    </div>

    <!-- Status Breakdown -->
    @if(isset($statusBreakdown) && $statusBreakdown->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Breakdown</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($statusBreakdown as $status)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <p class="text-sm text-gray-600 capitalize mb-2">{{ $status->status ?? 'N/A' }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($status->count ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">TZS {{ number_format($status->total ?? 0, 0) }}</p>
                @php
                    $totalStatus = $statusBreakdown->sum('total');
                    $percentage = $totalStatus > 0 ? ($status->total / $totalStatus) * 100 : 0;
                @endphp
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($percentage, 1) }}%</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Purchases Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Purchases List</h2>
            <span class="text-sm text-gray-500">{{ $purchases->total() ?? 0 }} total purchases</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase #</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchases ?? [] as $purchase)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $purchase->purchase_number ?? str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">{{ $purchase->supplier->name ?? 'N/A' }}</div>
                            @if($purchase->supplier)
                            <div class="text-xs text-gray-500">{{ $purchase->supplier->phone ?? '' }}</div>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $purchase->items->count() ?? 0 }} items</div>
                            <div class="text-xs text-gray-500">{{ number_format($purchase->items->sum('quantity') ?? 0) }} units</div>
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
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $purchase->purchase_date->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $purchase->created_at->setTimezone('Africa/Dar_es_Salaam')->format('h:i A') }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-semibold text-gray-900">TZS {{ number_format($purchase->total, 0) }}</div>
                            @if($purchase->due_amount > 0)
                            <div class="text-xs text-red-600">Due: TZS {{ number_format($purchase->due_amount, 0) }}</div>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('purchases.show', $purchase) }}" class="text-purple-600 hover:text-purple-900" title="View Details">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No purchases found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or create a new purchase.</p>
                        </td>
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
    // Purchases Trend Chart
    @if(isset($dailyPurchases) && $dailyPurchases->count() > 0)
    const purchasesTrendCtx = document.getElementById('purchasesTrendChart');
    if (purchasesTrendCtx) {
        const purchasesData = @json($dailyPurchases);
        const labels = purchasesData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const totals = purchasesData.map(item => parseFloat(item.total || 0));
        const counts = purchasesData.map(item => parseInt(item.count || 0));

        new Chart(purchasesTrendCtx, {
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
                    label: 'Purchase Count',
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
        const colors = ['rgb(147, 51, 234)', 'rgb(59, 130, 246)', 'rgb(34, 197, 94)', 'rgb(234, 179, 8)', 'rgb(239, 68, 68)'];

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

    // Export Functions
    function exportToPDF() {
        window.open('{{ route("purchases.index") }}?export=pdf&' + new URLSearchParams(window.location.search).toString(), '_blank');
    }
</script>
@endsection
