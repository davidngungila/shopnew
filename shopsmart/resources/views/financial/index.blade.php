@extends('layouts.app')

@section('title', '1️⃣ Financial Overview (Dashboard)')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">1️⃣ Financial Overview (Dashboard)</h1>
            <p class="text-gray-600 mt-1">This page gives a summary of the financial status.</p>
        </div>
        <div class="flex items-center space-x-3">
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option>This Month</option>
                <option>Last Month</option>
                <option>This Quarter</option>
                <option>This Year</option>
                <option>Custom Range</option>
            </select>
            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Export Report</span>
            </button>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Revenue</p>
                    <p class="text-3xl font-bold mt-2">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                    <div class="flex items-center mt-2 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>12.5% from last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662.662 0 00-.224 1.48 4.667 4.667 0 001.266 2.226 1.514 1.514 0 01-.213.294c-.133.13-.313.23-.527.31v1.09a3.37 3.37 0 001.562-.352c.386-.196.724-.47.99-.828.266-.357.448-.77.548-1.221.1-.45.15-.943.15-1.473V5a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676-.662.662.662 0 00-.224 1.48 4.667 4.667 0 001.266 2.226c.046.05.098.1.151.144v.093z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Total Expenses</p>
                    <p class="text-3xl font-bold mt-2">${{ number_format($totalExpenses ?? 0, 2) }}</p>
                    <div class="flex items-center mt-2 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>8.2% from last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-red-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Net Profit / Loss</p>
                    <p class="text-3xl font-bold mt-2">${{ number_format($netProfit ?? 0, 2) }}</p>
                    <div class="flex items-center mt-2 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                        </svg>
                        <span>18.3% from last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Assets</p>
                    <p class="text-3xl font-bold mt-2">${{ number_format($totalAssets ?? 0, 2) }}</p>
                    <div class="flex items-center mt-2 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>5.7% from last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row - Financial Health Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Liabilities</p>
                    <p class="text-2xl font-bold text-orange-600 mt-2">${{ number_format($totalLiabilities ?? 0, 2) }}</p>
                    <div class="mt-2">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span>Debt-to-Asset Ratio</span>
                            <span>32.5%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-orange-600 h-2 rounded-full" style="width: 32.5%"></div>
                        </div>
                    </div>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Cash Balance</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">${{ number_format($cashBalance ?? 0, 2) }}</p>
                    <div class="mt-2">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span>Liquidity Ratio</span>
                            <span>1.8</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 72%"></div>
                        </div>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Bank Balance</p>
                    <p class="text-2xl font-bold text-blue-600 mt-2">${{ number_format($bankBalance ?? 0, 2) }}</p>
                    <div class="mt-2">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span>Available Credit</span>
                            <span>$45,000</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 65%"></div>
                        </div>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Accounts Receivable</p>
                    <p class="text-2xl font-bold text-purple-600 mt-2">${{ number_format($accountsReceivable ?? 0, 2) }}</p>
                    <div class="mt-2">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span>Overdue</span>
                            <span>$8,450</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: 28%"></div>
                        </div>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue vs Expenses Trend -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Revenue vs Expenses Trend</h2>
                <select class="text-sm border border-gray-300 rounded px-3 py-1">
                    <option>Last 12 Months</option>
                    <option>Last 6 Months</option>
                    <option>Last 3 Months</option>
                </select>
            </div>
            <div class="h-80">
                <canvas id="revenueExpenseChart"></canvas>
            </div>
        </div>

        <!-- Cash Flow Analysis -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Cash Flow Analysis</h2>
                <select class="text-sm border border-gray-300 rounded px-3 py-1">
                    <option>Monthly</option>
                    <option>Quarterly</option>
                    <option>Yearly</option>
                </select>
            </div>
            <div class="h-80">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Financial Ratios and Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Profitability Ratios</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Gross Profit Margin</span>
                        <span class="text-sm font-semibold text-green-600">42.3%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 42.3%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Net Profit Margin</span>
                        <span class="text-sm font-semibold text-blue-600">18.7%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 18.7%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Return on Assets</span>
                        <span class="text-sm font-semibold text-purple-600">12.4%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 12.4%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Liquidity Ratios</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Current Ratio</span>
                        <span class="text-sm font-semibold text-green-600">2.4</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 80%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Quick Ratio</span>
                        <span class="text-sm font-semibold text-blue-600">1.8</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 72%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Cash Ratio</span>
                        <span class="text-sm font-semibold text-purple-600">0.9</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Efficiency Ratios</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Asset Turnover</span>
                        <span class="text-sm font-semibold text-green-600">3.2x</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 64%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Inventory Turnover</span>
                        <span class="text-sm font-semibold text-blue-600">8.7x</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 87%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Days Sales Outstanding</span>
                        <span class="text-sm font-semibold text-orange-600">42 days</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-600 h-2 rounded-full" style="width: 42%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Expense Categories with Enhanced Visualization -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Expense Categories</h2>
            <div class="h-64">
                <canvas id="expenseCategoriesChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Expense Breakdown</h2>
            <div class="space-y-3">
                @forelse($topExpenseCategories ?? [] as $category)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 rounded-full" style="background-color: {{ ['#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444'][$loop->index % 5] }}"></div>
                        <div>
                            <span class="text-sm font-medium text-gray-900">{{ $category->expenseCategory->name ?? 'Uncategorized' }}</span>
                            <div class="text-xs text-gray-500">{{ $category->count }} transactions</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-red-600">${{ number_format($category->total, 2) }}</div>
                        <div class="text-xs text-gray-500">{{ round(($category->total / ($totalExpenses ?? 1)) * 100, 1) }}%</div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">No expense categories found</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions and Invoices -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Transactions</h2>
                <a href="#" class="text-sm text-green-600 hover:text-green-700">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions ?? [] as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $transaction->transaction_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-right {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500">No transactions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Invoices -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Invoices</h2>
                <a href="#" class="text-sm text-green-600 hover:text-green-700">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentInvoices ?? [] as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">#{{ $invoice->id }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $invoice->customer->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-right text-gray-900">${{ number_format($invoice->total, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $invoice->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($invoice->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">No invoices found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Outstanding Payments with Enhanced Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Outstanding Payments</h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Total Outstanding:</span>
                <span class="text-lg font-bold text-orange-600">${{ number_format($outstandingPayments->sum('total') ?? 0, 2) }}</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sale Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Ago</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($outstandingPayments ?? [] as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $payment->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->customer->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $payment->created_at->diffInDays(now()) > 30 ? 'bg-red-100 text-red-800' : ($payment->created_at->diffInDays(now()) > 7 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ $payment->created_at->diffInDays(now()) }} days
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right text-orange-600">${{ number_format($payment->total, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                Outstanding
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-900">Send Reminder</button>
                                <button class="text-green-600 hover:text-green-900">Record Payment</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No outstanding payments</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue vs Expenses Chart
    const revenueExpenseCtx = document.getElementById('revenueExpenseChart');
    if (revenueExpenseCtx) {
        const monthlyData = @json($monthlyIncomeExpense ?? []);
        const labels = Object.keys(monthlyData).map(month => {
            const [year, monthNum] = month.split('-');
            const date = new Date(year, monthNum - 1);
            return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
        });
        const incomeData = Object.values(monthlyData).map(data => data.income);
        const expenseData = Object.values(monthlyData).map(data => data.expenses);

        new Chart(revenueExpenseCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue',
                    data: incomeData,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.1,
                    fill: true
                }, {
                    label: 'Expenses',
                    data: expenseData,
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Cash Flow Chart
    const cashFlowCtx = document.getElementById('cashFlowChart');
    if (cashFlowCtx) {
        new Chart(cashFlowCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Operating Cash Flow',
                    data: [12000, 15000, 13000, 18000, 16000, 21000],
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                }, {
                    label: 'Investing Cash Flow',
                    data: [-5000, -3000, -8000, -2000, -4000, -6000],
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                }, {
                    label: 'Financing Cash Flow',
                    data: [2000, 1000, 3000, 1500, 2500, 4000],
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Expense Categories Pie Chart
    const expenseCategoriesCtx = document.getElementById('expenseCategoriesChart');
    if (expenseCategoriesCtx) {
        const categoryData = @json($topExpenseCategories ?? []);
        new Chart(expenseCategoriesCtx, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(cat => cat.expenseCategory?.name || 'Uncategorized'),
                datasets: [{
                    data: categoryData.map(cat => cat.total),
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(139, 92, 246)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(236, 72, 153)',
                        'rgb(14, 165, 233)',
                        'rgb(168, 85, 247)'
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }
});
</script>
@endsection











