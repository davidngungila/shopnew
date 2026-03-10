@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="space-y-8" x-data="reportsDashboard()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Reports & Analytics</h1>
            <p class="text-gray-600 mt-1">Comprehensive business intelligence and reporting</p>
        </div>
        <div class="flex gap-2">
            <button @click="generateCustomReport()" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2 hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-plus"></i>
                <span>Custom Report</span>
            </button>
            <button @click="exportAllReports()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-download mr-2"></i>Export All
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Reports</p>
                    <p class="text-2xl font-bold text-blue-600">12</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-chart-bar text-blue-500"></i> 
                        Available reports
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Scheduled Reports</p>
                    <p class="text-2xl font-bold text-green-600">8</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-clock text-green-500"></i> 
                        Automated reports
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">This Month</p>
                    <p class="text-2xl font-bold text-purple-600">47</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-arrow-up text-green-500"></i> 
                        +12% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Data Freshness</p>
                    <p class="text-2xl font-bold text-orange-600">Live</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-sync text-green-500"></i> 
                        Real-time data
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-database text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Reports Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Financial Reports</h2>
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="text-sm text-gray-500">Core financial statements</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Profit & Loss -->
            <a href="{{ route('financial-statements.profit-loss') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-green-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-chart-line text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Profit & Loss</h3>
                        <p class="text-sm text-gray-600">Revenue, expenses, profit analysis</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-green-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 2 hours ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Available</span>
                </div>
            </a>

            <!-- Balance Sheet -->
            <a href="{{ route('financial-statements.balance-sheet') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-blue-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-balance-scale text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Balance Sheet</h3>
                        <p class="text-sm text-gray-600">Assets, liabilities, equity</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 2 hours ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Available</span>
                </div>
            </a>

            <!-- Trial Balance -->
            <a href="{{ route('financial-statements.trial-balance') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-purple-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-list-alt text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Trial Balance</h3>
                        <p class="text-sm text-gray-600">Account balances verification</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-purple-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 2 hours ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Available</span>
                </div>
            </a>

            <!-- Cash Flow -->
            <a href="#" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-orange-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                        <i class="fas fa-water text-orange-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">Cash Flow</h3>
                        <p class="text-sm text-gray-600">Cash movement analysis</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-orange-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 1 hour ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Available</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Sales Reports Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Sales Reports</h2>
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="text-sm text-gray-500">Sales performance and analytics</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Sales Analytics -->
            <a href="{{ route('reports.sales') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-purple-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-shopping-cart text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Sales Analytics</h3>
                        <p class="text-sm text-gray-600">Detailed sales reports</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-purple-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 30 min ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Live</span>
                </div>
            </a>

            <!-- Customer Reports -->
            <a href="{{ route('reports.customers') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-pink-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center group-hover:bg-pink-200 transition-colors">
                        <i class="fas fa-users text-pink-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-pink-600 transition-colors">Customer Reports</h3>
                        <p class="text-sm text-gray-600">Customer analysis & statements</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-pink-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 1 hour ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Live</span>
                </div>
            </a>

            <!-- Product Performance -->
            <a href="#" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-indigo-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                        <i class="fas fa-box text-indigo-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Product Performance</h3>
                        <p class="text-sm text-gray-600">Best/worst selling products</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-indigo-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 2 hours ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Live</span>
                </div>
            </a>

            <!-- Sales by Region -->
            <a href="#" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-teal-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-200 transition-colors">
                        <i class="fas fa-map-marked-alt text-teal-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-teal-600 transition-colors">Sales by Region</h3>
                        <p class="text-sm text-gray-600">Geographic sales analysis</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-teal-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 3 hours ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Live</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Operations Reports Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Operations Reports</h2>
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="text-sm text-gray-500">Business operations insights</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Inventory Reports -->
            <a href="{{ route('reports.inventory') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-blue-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-warehouse text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Inventory Reports</h3>
                        <p class="text-sm text-gray-600">Stock levels & movements</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 15 min ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Live</span>
                </div>
            </a>

            <!-- Purchase Reports -->
            <a href="{{ route('reports.purchases') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-green-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-truck text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Purchase Reports</h3>
                        <p class="text-sm text-gray-600">Purchase analysis & trends</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-green-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 1 hour ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Live</span>
                </div>
            </a>

            <!-- Supplier Reports -->
            <a href="{{ route('reports.suppliers') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-yellow-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                        <i class="fas fa-handshake text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-yellow-600 transition-colors">Supplier Reports</h3>
                        <p class="text-sm text-gray-600">Supplier performance & statements</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-yellow-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 2 hours ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Live</span>
                </div>
            </a>

            <!-- Employee Reports -->
            <a href="#" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-red-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                        <i class="fas fa-user-tie text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-red-600 transition-colors">Employee Reports</h3>
                        <p class="text-sm text-gray-600">Staff performance & attendance</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-red-600 transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-clock mr-1"></i>Updated 4 hours ago</span>
                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Live</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Advanced Analytics Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Advanced Analytics</h2>
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="text-sm text-gray-500">Deep business insights</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Business Intelligence -->
            <a href="#" class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center group-hover:bg-opacity-30 transition-colors">
                        <i class="fas fa-brain text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-white group-hover:text-purple-100 transition-colors">Business Intelligence</h3>
                        <p class="text-sm text-purple-100">AI-powered insights</p>
                    </div>
                    <i class="fas fa-arrow-right text-purple-200 group-hover:text-white transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-purple-100">
                    <span><i class="fas fa-sparkles mr-1"></i>Advanced</span>
                    <span class="text-yellow-300"><i class="fas fa-star mr-1"></i>Premium</span>
                </div>
            </a>

            <!-- Custom Dashboard -->
            <a href="#" class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center group-hover:bg-opacity-30 transition-colors">
                        <i class="fas fa-th-large text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-white group-hover:text-blue-100 transition-colors">Custom Dashboard</h3>
                        <p class="text-sm text-blue-100">Build your own reports</p>
                    </div>
                    <i class="fas fa-arrow-right text-blue-200 group-hover:text-white transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-blue-100">
                    <span><i class="fas fa-cog mr-1"></i>Configurable</span>
                    <span class="text-green-300"><i class="fas fa-check mr-1"></i>Available</span>
                </div>
            </a>

            <!-- Data Export -->
            <a href="#" class="bg-gradient-to-br from-green-500 to-teal-500 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center group-hover:bg-opacity-30 transition-colors">
                        <i class="fas fa-file-export text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-white group-hover:text-green-100 transition-colors">Data Export Center</h3>
                        <p class="text-sm text-green-100">Bulk export capabilities</p>
                    </div>
                    <i class="fas fa-arrow-right text-green-200 group-hover:text-white transition-colors"></i>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-green-100">
                    <span><i class="fas fa-download mr-1"></i>Multi-format</span>
                    <span class="text-yellow-300"><i class="fas fa-bolt mr-1"></i>Fast</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recently Generated Reports</h3>
            <button @click="viewAllHistory()" class="text-sm text-blue-600 hover:text-blue-800">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-pdf text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Monthly Sales Report - November 2025</p>
                        <p class="text-xs text-gray-500">Generated 2 hours ago • 2.4 MB</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="text-blue-600 hover:text-blue-800" title="Download">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="text-gray-600 hover:text-gray-800" title="Share">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-excel text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Inventory Status Report</p>
                        <p class="text-xs text-gray-500">Generated 5 hours ago • 856 KB</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="text-blue-600 hover:text-blue-800" title="Download">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="text-gray-600 hover:text-gray-800" title="Share">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-csv text-purple-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Customer Analysis Report</p>
                        <p class="text-xs text-gray-500">Generated yesterday • 1.2 MB</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="text-blue-600 hover:text-blue-800" title="Download">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="text-gray-600 hover:text-gray-800" title="Share">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function reportsDashboard() {
    return {
        generateCustomReport() {
            // Open custom report builder
            alert('Custom Report Builder would open here');
        },
        exportAllReports() {
            // Export all reports
            alert('Export All Reports functionality would be triggered here');
        },
        viewAllHistory() {
            // View report history
            alert('Report History would open here');
        }
    }
}
</script>
@endsection
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Purchase Reports</h3>
                        <p class="text-sm text-gray-600">Track purchase orders and suppliers</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Financial Reports Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Financial Reports</h2>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Profit & Loss -->
            <a href="{{ route('reports.profit-loss') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-orange-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">Profit & Loss</h3>
                        <p class="text-sm text-gray-600">Financial performance analysis</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Inventory Reports Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Inventory Reports</h2>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Inventory Reports -->
            <a href="{{ route('reports.inventory') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-green-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Inventory Report</h3>
                        <p class="text-sm text-gray-600">Stock levels and product analytics</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Account Statements Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <h2 class="text-xl font-semibold text-gray-900">Account Statements</h2>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Customer Statements -->
            <a href="{{ route('reports.customers') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-pink-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center group-hover:bg-pink-200 transition-colors">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-pink-600 transition-colors">Customer Statements</h3>
                        <p class="text-sm text-gray-600">Generate customer account statements</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-pink-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- Supplier Statements -->
            <a href="{{ route('reports.suppliers') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-indigo-300 transition-all group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Supplier Statements</h3>
                        <p class="text-sm text-gray-600">Generate supplier account statements</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
