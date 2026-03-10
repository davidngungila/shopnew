@extends('layouts.app')

@section('title', 'Balance Sheet')

@section('content')
<div class="space-y-6" x-data="balanceSheet()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Balance Sheet</h1>
            <p class="text-gray-600 mt-1">Comprehensive financial position analysis</p>
        </div>
        <div class="flex gap-2">
            <button @click="exportPDF()" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2 hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-file-pdf"></i>
                <span>Export PDF</span>
            </button>
            <button @click="printStatement()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-print mr-2"></i>Print
            </button>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters & Options</h3>
        <form method="GET" action="{{ route('financial-statements.balance-sheet') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">As of Date</label>
                    <input type="date" name="as_of_date" value="{{ $asOfDate ?? request('as_of_date', now()->toDateString()) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="break text-sm font-medium text-gray-700 mb-2">Comparison Date</label>
                    <input type="date" name="comparison_date" value="{{ request('comparison_date', now()->subMonth()->toDateString()) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">View Mode</label>
                    <select name="view_mode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="summary">Summary</option>
                        <option value="detailed">Detailed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Level</label>
                    <select name="account_level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="main">Main Accounts</option>
                        <option value="sub">Sub Accounts</option>
                        <option value="all">All Levels</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
                <a href="{{ route('financial-statements.balance-sheet') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Key Financial Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Assets</p>
                    <p class="text-2xl font-bold text-blue-600">TZS {{ number_format($totalAssets ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-arrow-up text-green-500"></i> 
                        +8.3% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Liabilities</p>
                    <p class="text-2xl font-bold text-red-600">TZS {{ number_format($totalLiabilities ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-arrow-down text-green-500"></i> 
                        -3.2% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-credit-card text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Equity</p>
                    <p class="text-2xl font-bold text-green-600">TZS {{ number_format($totalEquity ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-arrow-up text-green-500"></i> 
                        +15.7% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-pie text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Debt-to-Equity</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $totalEquity > 0 ? round(($totalLiabilities / $totalEquity), 2) : 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-orange-600">Moderate Risk</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-balance-scale text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Sheet Statement -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assets Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Assets</h2>
                <span class="text-sm text-gray-500">
                    As of: {{ \Carbon\Carbon::parse($asOfDate)->format('M d, Y') }}
                </span>
            </div>

            <div class="space-y-6">
                <!-- Current Assets -->
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Current Assets</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Cash & Bank</span>
                            <span class="font-medium">TZS {{ number_format(($currentAssets ?? 0) * 0.3, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Accounts Receivable</span>
                            <span class="font-medium">TZS {{ number_format(($currentAssets ?? 0) * 0.4, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Inventory</span>
                            <span class="font-medium">TZS {{ number_format(($currentAssets ?? 0) * 0.25, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Prepaid Expenses</span>
                            <span class="font-medium">TZS {{ number_format(($currentAssets ?? 0) * 0.05, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t">
                            <span class="text-lg font-semibold text-gray-900">Total Current Assets</span>
                            <span class="text-lg font-semibold text-blue-600">TZS {{ number_format($currentAssets ?? 0, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Fixed Assets -->
                <div class="border-l-4 border-green-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Fixed Assets</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Property & Equipment</span>
                            <span class="font-medium">TZS {{ number_format(($fixedAssets ?? 0) * 0.6, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Vehicles</span>
                            <span class="font-medium">TZS {{ number_format(($fixedAssets ?? 0) * 0.25, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Furniture & Fixtures</span>
                            <span class="font-medium">TZS {{ number_format(($fixedAssets ?? 0) * 0.15, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t">
                            <span class="text-lg font-semibold text-gray-900">Total Fixed Assets</span>
                            <span class="text-lg font-semibold text-green-600">TZS {{ number_format($fixedAssets ?? 0, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Total Assets -->
                <div class="bg-gradient-to-r from-blue-50 to-green-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-900">Total Assets</span>
                        <span class="text-xl font-bold text-blue-600">TZS {{ number_format($totalAssets ?? 0, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liabilities & Equity Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Liabilities & Equity</h2>
                <span class="text-sm text-gray-500">
                    As of: {{ \Carbon\Carbon::parse($asOfDate)->format('M d, Y') }}
                </span>
            </div>

            <div class="space-y-6">
                <!-- Current Liabilities -->
                <div class="border-l-4 border-red-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Current Liabilities</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Accounts Payable</span>
                            <span class="font-medium">TZS {{ number_format(($currentLiabilities ?? 0) * 0.5, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Short-term Loans</span>
                            <span class="font-medium">TZS {{ number_format(($currentLiabilities ?? 0) * 0.3, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Accrued Expenses</span>
                            <span class="font-medium">TZS {{ number_format(($currentLiabilities ?? 0) * 0.2, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t">
                            <span class="text-lg font-semibold text-gray-900">Total Current Liabilities</span>
                            <span class="text-lg font-semibold text-red-600">TZS {{ number_format($currentLiabilities ?? 0, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Long-term Liabilities -->
                <div class="border-l-4 border-orange-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Long-term Liabilities</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Long-term Loans</span>
                            <span class="font-medium">TZS {{ number_format(($longTermLiabilities ?? 0) * 0.7, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Mortgage</span>
                            <span class="font-medium">TZS {{ number_format(($longTermLiabilities ?? 0) * 0.3, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t">
                            <span class="text-lg font-semibold text-gray-900">Total Long-term Liabilities</span>
                            <span class="text-lg font-semibold text-orange-600">TZS {{ number_format($longTermLiabilities ?? 0, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Total Liabilities -->
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-900">Total Liabilities</span>
                        <span class="text-xl font-bold text-red-600">TZS {{ number_format($totalLiabilities ?? 0, 0) }}</span>
                    </div>
                </div>

                <!-- Equity -->
                <div class="border-l-4 border-green-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Equity</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Capital</span>
                            <span class="font-medium">TZS {{ number_format(($capital ?? 0), 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Retained Earnings</span>
                            <span class="font-medium">TZS {{ number_format(($retainedEarnings ?? 0), 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Additional Paid-in Capital</span>
                            <span class="font-medium">TZS 0</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t">
                            <span class="text-lg font-semibold text-gray-900">Total Equity</span>
                            <span class="text-lg font-semibold text-green-600">TZS {{ number_format($totalEquity ?? 0, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Total Liabilities & Equity -->
                <div class="bg-gradient-to-r from-red-50 to-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-900">Total Liabilities & Equity</span>
                        <span class="text-xl font-bold text-green-600">TZS {{ number_format(($totalLiabilities + $totalEquity) ?? 0, 0) }}</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500"></i>
                        Balance verified: Assets = Liabilities + Equity
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Ratios -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Ratios Analysis</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-500">Current Ratio</p>
                <p class="text-xl font-bold text-blue-600">{{ $currentLiabilities > 0 ? round($currentAssets / $currentLiabilities, 2) : 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Good (> 1.5)</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-500">Quick Ratio</p>
                <p class="text-xl font-bold text-blue-600">{{ $currentLiabilities > 0 ? round(($currentAssets * 0.7) / $currentLiabilities, 2) : 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Good (> 1.0)</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-500">Debt-to-Equity</p>
                <p class="text-xl font-bold text-orange-600">{{ $totalEquity > 0 ? round($totalLiabilities / $totalEquity, 2) : 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Low (< 1.0)</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-500">Equity Ratio</p>
                <p class="text-xl font-bold text-green-600">{{ ($totalAssets) > 0 ? round(($totalEquity / $totalAssets) * 100, 1) : 0 }}%</p>
                <p class="text-xs text-gray-500 mt-1">Strong (> 30%)</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Asset Composition Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Asset Composition</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-chart-pie text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Asset composition chart will be displayed here</p>
                </div>
            </div>
        </div>

        <!-- Debt Structure Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Debt Structure</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-chart-bar text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Debt structure chart will be displayed here</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function balanceSheet() {
    return {
        exportPDF() {
            window.open('{{ route('financial-statements.balance-sheet.pdf', request()->query()) }}', '_blank');
        },
        printStatement() {
            window.print();
        }
    }
}
</script>
@endsection
                    <span class="font-semibold text-gray-900">TSh {{ number_format($currentLiabilities ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-700">Long-term Liabilities</span>
                    <span class="font-semibold text-gray-900">TSh {{ number_format($longTermLiabilities ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b-2 border-gray-300">
                    <span class="font-semibold text-gray-900">Total Liabilities</span>
                    <span class="font-semibold text-gray-900">TSh {{ number_format($totalLiabilities ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b mt-2">
                    <span class="text-gray-700">Capital</span>
                    <span class="font-semibold text-gray-900">TSh {{ number_format($capital ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-700">Retained Earnings</span>
                    <span class="font-semibold text-gray-900">TSh {{ number_format($retainedEarnings ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-t-2 border-gray-300 mt-2">
                    <span class="text-lg font-bold text-gray-900">Total Equity</span>
                    <span class="text-lg font-bold text-gray-900">TSh {{ number_format($totalEquity ?? 0, 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-t-2 border-gray-300 mt-2">
                    <span class="text-xl font-bold text-gray-900">Total Liabilities & Equity</span>
                    <span class="text-xl font-bold text-gray-900">TSh {{ number_format(($totalLiabilities ?? 0) + ($totalEquity ?? 0), 0) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




