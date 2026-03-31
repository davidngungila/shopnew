@extends('layouts.app')

@section('title', 'Warehouse Capacity Report')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Capacity Report</h1>
            <p class="text-gray-600 mt-1">Warehouse capacity analysis and utilization</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="exportReport()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export Report</span>
            </button>
            <a href="{{ route('warehouses.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Warehouses</span>
            </a>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-blue-600">Total Warehouses</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $warehouseStats->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-green-600">Total Products</p>
                    <p class="text-2xl font-bold text-green-900">{{ number_format($warehouseStats->sum('total_products')) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-purple-600">Total Stock</p>
                    <p class="text-2xl font-bold text-purple-900">{{ number_format($warehouseStats->sum('total_stock')) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-orange-600">Total Value</p>
                    <p class="text-2xl font-bold text-orange-900">TZS {{ number_format($warehouseStats->sum('total_value'), 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Warehouse Capacity Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Warehouse Capacity Details</h3>
                <div class="flex items-center space-x-3">
                    <button onclick="refreshData()" class="text-sm text-gray-600 hover:text-gray-800">Refresh</button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warehouse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Low Stock Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($warehouseStats as $stat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $stat['warehouse']->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $stat['warehouse']->address ?? 'No address' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat['total_products'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($stat['total_stock']) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TZS {{ number_format($stat['total_value'], 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium {{ $stat['low_stock_items'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $stat['low_stock_items'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $stat['warehouse']->capacity ? number_format($stat['warehouse']->capacity) : 'Unlimited' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($stat['warehouse']->capacity)
                            <div class="flex items-center">
                                <div class="flex-1 mr-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($stat['capacity_utilization'], 100) }}%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($stat['capacity_utilization'], 1) }}%</span>
                            </div>
                            @else
                            <span class="text-sm text-gray-500">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($stat['warehouse']->capacity)
                                @if($stat['capacity_utilization'] >= 90)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Critical</span>
                                @elseif($stat['capacity_utilization'] >= 75)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Warning</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Good</span>
                                @endif
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No Limit</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Capacity Utilization Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Capacity Utilization Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($warehouseStats->where('warehouse.capacity', '>', 0) as $stat)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">{{ $stat['warehouse']->name }}</h4>
                    <span class="text-sm font-medium {{ $stat['capacity_utilization'] >= 90 ? 'text-red-600' : ($stat['capacity_utilization'] >= 75 ? 'text-orange-600' : 'text-green-600') }}">
                        {{ number_format($stat['capacity_utilization'], 1) }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="h-3 rounded-full transition-all duration-300 {{ $stat['capacity_utilization'] >= 90 ? 'bg-red-500' : ($stat['capacity_utilization'] >= 75 ? 'bg-orange-500' : 'bg-green-500') }}" 
                         style="width: {{ min($stat['capacity_utilization'], 100) }}%"></div>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    {{ number_format($stat['total_stock']) }} / {{ number_format($stat['warehouse']->capacity) }} units
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function exportReport() {
    window.location.href = '{{ url("warehouses/capacity-report/export") }}';
}

function refreshData() {
    location.reload();
}
</script>
@endsection
