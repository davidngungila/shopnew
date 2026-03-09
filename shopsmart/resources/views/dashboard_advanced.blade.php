@extends('layouts.app')

@section('title', 'Advanced Dashboard')

@section('content')
<div class="space-y-6" x-data="dashboardApp()">
    <!-- Advanced Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Advanced Dashboard</h1>
            <p class="text-gray-600 mt-1">
                {{ $now->format('l, F d, Y') }} - {{ $now->format('h:i A') }} EAT
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button @click="refreshData()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Refresh</span>
            </button>
            <select x-model="dateRange" @change="updateCharts()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="7">Last 7 Days</option>
                <option value="30">Last 30 Days</option>
                <option value="90">Last 90 Days</option>
                <option value="365">Last Year</option>
            </select>
            <a href="{{ route('pos.index') }}" class="px-4 py-2 rounded-lg flex items-center space-x-2 text-white" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Sale</span>
            </a>
        </div>
    </div>

    <!-- Advanced KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Revenue Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Revenue</p>
                    <p class="text-3xl font-bold mt-2">TZS {{ number_format($todaySales ?? 0, 0) }}</p>
                    <div class="flex items-center mt-2 text-sm">
                        @php
                            $growth = $salesGrowth ?? 0;
                            $growth = is_numeric($growth) && is_finite($growth) ? $growth : 0;
                        @endphp
                        @if($growth >= 0)
                        <span class="text-blue-100 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ number_format(abs($growth), 1) }}% from yesterday
                        </span>
                        @else
                        <span class="text-blue-100 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ number_format(abs($growth), 1) }}% from yesterday
                        </span>
                        @endif
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662.662.662 0 00-.224 1.48 4.667 4.667 0 001.266 2.226 1.514 1.514 0 01-.213.294c-.133.13-.313.23-.527.31v1.09a3.37 3.37 0 001.562-.352c.386-.196.724-.47.99-.828.266-.357.448-.77.548-1.221.1-.45.15-.943.15-1.473V5a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676-.662.662.662 0 00-.224 1.48 4.667 4.667 0 001.266 2.226C.046.05.098.1.151.144v.093z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Orders</p>
                    <p class="text-3xl font-bold mt-2">{{ $todayOrders ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-sm text-green-100">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 00-2 2v6a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-1a1 1 0 100-2h1a4 4 0 014 4v6a4 4 0 01-4 4H6a4 4 0 01-4-4V7a4 4 0 014-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $pendingOrders ?? 0 }} pending
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Active Customers</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalCustomers ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-sm text-purple-100">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                        +{{ $newCustomersToday ?? 0 }} today
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Total Products</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalProducts ?? 0 }}</p>
                    <div class="flex items-center mt-2 text-sm text-orange-100">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        {{ $lowStockCount ?? 0 }} low stock
                    </div>
                </div>
                <div class="w-12 h-12 bg-orange-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600">Profit Margin</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($profitMargin ?? 0, 1) }}%</p>
                    <p class="text-xs text-gray-500 mt-1">30-day average</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600">Stock Value</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">TZS {{ number_format($totalStockValue ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activeProducts ?? 0 }} items</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600">Pending Quotes</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $pendingQuotations ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">TZS {{ number_format($quotationValue ?? 0, 0) }} value</p>
                </div>
                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600">Avg Order Value</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">TZS {{ number_format($avgOrderValue ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Last 30 days</p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue & Orders Chart -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Revenue & Orders Trend</h2>
                <div class="flex items-center space-x-2">
                    <button @click="changeChartType('line')" :class="chartType === 'line' ? 'bg-purple-100 text-purple-700' : 'text-gray-500'" class="px-3 py-1 rounded text-sm">Line</button>
                    <button @click="changeChartType('bar')" :class="chartType === 'bar' ? 'bg-purple-100 text-purple-700' : 'text-gray-500'" class="px-3 py-1 rounded text-sm">Bar</button>
                    <button @click="changeChartType('area')" :class="chartType === 'area' ? 'bg-purple-100 text-purple-700' : 'text-gray-500'" class="px-3 py-1 rounded text-sm">Area</button>
                </div>
            </div>
            <div style="position: relative; height: 350px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Revenue Distribution -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Revenue Distribution</h2>
            <div style="position: relative; height: 350px;">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Category Performance -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Category Performance</h2>
                <button @click="toggleCategoryView()" class="text-sm text-purple-600 hover:text-purple-800">
                    {{ categoryView === 'bar' ? 'Show as Pie' : 'Show as Bar' }}
                </button>
            </div>
            <div style="position: relative; height: 300px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Payment Methods Breakdown -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Methods</h2>
            <div style="position: relative; height: 300px;">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performers Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Products</h2>
            <div class="space-y-3">
                @forelse($topProducts ?? [] as $index => $product)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-purple-600">#{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $product->sku ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">{{ $product->total_quantity ?? 0 }}</p>
                        <p class="text-xs text-gray-500">TZS {{ number_format($product->total_revenue ?? 0, 0) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No sales data available</p>
                @endforelse
            </div>
        </div>

        <!-- Top Customers -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Customers</h2>
            <div class="space-y-3">
                @forelse($topCustomers ?? [] as $index => $customer)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-green-600">#{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $customer->name }}</p>
                            <p class="text-xs text-gray-500">{{ $customer->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">{{ $customer->order_count ?? 0 }} orders</p>
                        <p class="text-xs text-gray-500">TZS {{ number_format($customer->total_spent ?? 0, 0) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No customer data available</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
            <div class="space-y-3">
                @forelse($recentSales ?? [] as $sale)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Order #{{ $sale->id ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $sale->customer_name ?? 'Walk-in' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">TZS {{ number_format($sale->total ?? 0, 0) }}</p>
                        <p class="text-xs text-gray-500">{{ $sale->created_at?->format('H:i') ?? 'N/A' }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-lg p-4 text-white">
            <p class="text-teal-100 text-sm">Conversion Rate</p>
            <p class="text-2xl font-bold">{{ number_format($conversionRate ?? 0, 1) }}%</p>
        </div>
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-4 text-white">
            <p class="text-red-100 text-sm">Returns</p>
            <p class="text-2xl font-bold">{{ $returnsCount ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg p-4 text-white">
            <p class="text-indigo-100 text-sm">Expenses</p>
            <p class="text-2xl font-bold">TZS {{ number_format($totalExpenses ?? 0, 0) }}</p>
        </div>
        <div class="bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg p-4 text-white">
            <p class="text-pink-100 text-sm">Net Profit</p>
            <p class="text-2xl font-bold">TZS {{ number_format($netProfit ?? 0, 0) }}</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function dashboardApp() {
    return {
        dateRange: '30',
        chartType: 'line',
        categoryView: 'bar',
        charts: {},
        
        init() {
            this.initCharts();
            this.setupEventListeners();
        },
        
        initCharts() {
            this.initRevenueChart();
            this.initDistributionChart();
            this.initCategoryChart();
            this.initPaymentChart();
        },
        
        initRevenueChart() {
            const ctx = document.getElementById('revenueChart');
            if (!ctx) return;
            
            const labels = this.generateDateLabels(parseInt(this.dateRange));
            const revenueData = this.generateRandomData(labels.length, 50000, 200000);
            const ordersData = this.generateRandomData(labels.length, 10, 50);
            
            this.charts.revenue = new Chart(ctx, {
                type: this.chartType,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue (TZS)',
                        data: revenueData,
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: this.chartType === 'area' ? 'rgba(147, 51, 234, 0.2)' : 'rgba(147, 51, 234, 0.1)',
                        tension: 0.4,
                        fill: this.chartType === 'area',
                        yAxisID: 'y',
                        borderWidth: 2,
                    }, {
                        label: 'Orders',
                        data: ordersData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: false,
                        yAxisID: 'y1',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
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
                                    return 'TZS ' + (value / 1000).toFixed(0) + 'K';
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
                            }
                        }
                    }
                }
            });
        },
        
        initDistributionChart() {
            const ctx = document.getElementById('distributionChart');
            if (!ctx) return;
            
            this.charts.distribution = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Products', 'Services', 'Shipping', 'Tax', 'Other'],
                    datasets: [{
                        data: [45, 25, 15, 10, 5],
                        backgroundColor: [
                            'rgb(147, 51, 234)',
                            'rgb(59, 130, 246)',
                            'rgb(34, 197, 94)',
                            'rgb(251, 146, 60)',
                            'rgb(244, 63, 94)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        
        initCategoryChart() {
            const ctx = document.getElementById('categoryChart');
            if (!ctx) return;
            
            this.charts.category = new Chart(ctx, {
                type: this.categoryView,
                data: {
                    labels: ['Electronics', 'Clothing', 'Food', 'Books', 'Other'],
                    datasets: [{
                        label: 'Revenue',
                        data: [120000, 80000, 60000, 40000, 30000],
                        backgroundColor: [
                            'rgba(147, 51, 234, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(244, 63, 94, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: this.categoryView === 'pie',
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        
        initPaymentChart() {
            const ctx = document.getElementById('paymentChart');
            if (!ctx) return;
            
            this.charts.payment = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: ['Cash', 'Card', 'Mobile Money', 'Bank Transfer'],
                    datasets: [{
                        data: [150000, 120000, 80000, 50000],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(147, 51, 234, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        
        changeChartType(type) {
            this.chartType = type;
            this.charts.revenue.config.type = type;
            this.charts.revenue.config.data.datasets[0].fill = type === 'area';
            this.charts.revenue.update();
        },
        
        toggleCategoryView() {
            this.categoryView = this.categoryView === 'bar' ? 'pie' : 'bar';
            this.charts.category.config.type = this.categoryView;
            this.charts.category.options.plugins.legend.display = this.categoryView === 'pie';
            this.charts.category.update();
        },
        
        updateCharts() {
            // Update all charts with new date range
            const labels = this.generateDateLabels(parseInt(this.dateRange));
            const revenueData = this.generateRandomData(labels.length, 50000, 200000);
            const ordersData = this.generateRandomData(labels.length, 10, 50);
            
            this.charts.revenue.data.labels = labels;
            this.charts.revenue.data.datasets[0].data = revenueData;
            this.charts.revenue.data.datasets[1].data = ordersData;
            this.charts.revenue.update();
        },
        
        refreshData() {
            // Simulate data refresh
            this.updateCharts();
            this.showNotification('Dashboard data refreshed', 'success');
        },
        
        generateDateLabels(days) {
            const labels = [];
            const today = new Date();
            for (let i = days - 1; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(date.getDate() - i);
                labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            }
            return labels;
        },
        
        generateRandomData(count, min, max) {
            const data = [];
            for (let i = 0; i < count; i++) {
                data.push(Math.floor(Math.random() * (max - min + 1)) + min);
            }
            return data;
        },
        
        setupEventListeners() {
            // Setup any additional event listeners
        },
        
        showNotification(message, type = 'info') {
            // Simple notification system
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg text-white z-50 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                'bg-blue-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    }
}
</script>
@endsection
