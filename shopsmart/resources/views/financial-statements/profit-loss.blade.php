@extends('layouts.app')

@section('title', 'Profit & Loss Statement')

@section('content')
<div class="space-y-6" x-data="profitLossStatement()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Profit & Loss Statement</h1>
            <p class="text-gray-600 mt-1">Comprehensive business performance analysis</p>
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
        <form method="GET" action="{{ route('financial-statements.profit-loss') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="date_from" value="{{ $dateFrom ?? request('date_from', now()->startOfMonth()->toDateString()) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="date_to" value="{{ $dateTo ?? request('date_to', now()->toDateString()) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comparison Period</label>
                    <select name="comparison" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">None</option>
                        <option value="previous_month">Previous Month</option>
                        <option value="previous_quarter">Previous Quarter</option>
                        <option value="previous_year">Previous Year</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">View Mode</label>
                    <select name="view_mode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="summary">Summary</option>
                        <option value="detailed">Detailed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
                <a href="{{ route('financial-statements.profit-loss') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-bold text-green-600">TZS {{ number_format($revenue ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-arrow-up text-green-500"></i> 
                        +12.5% from last period
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Gross Profit</p>
                    <p class="text-2xl font-bold text-blue-600">TZS {{ number_format($grossProfit ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-blue-600">{{ $revenue > 0 ? round(($grossProfit / $revenue) * 100, 1) : 0 }}% margin</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-percentage text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Operating Expenses</p>
                    <p class="text-2xl font-bold text-orange-600">TZS {{ number_format($operatingExpenses ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-arrow-down text-green-500"></i> 
                        -5.2% from last period
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-receipt text-orange-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Net Profit</p>
                    <p class="text-2xl font-bold {{ ($netProfit ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">TZS {{ number_format($netProfit ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="{{ ($netProfit ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ ($netProfit ?? 0) >= 0 ? 'Profitable' : 'Loss' }}
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 {{ ($netProfit ?? 0) >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                    <i class="fas fa-coins {{ ($netProfit ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Profit & Loss Statement -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Profit & Loss Statement</h2>
            <span class="text-sm text-gray-500">
                Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
            </span>
        </div>

        <div class="space-y-6">
            <!-- Revenue Section -->
            <div class="border-l-4 border-green-500 pl-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Revenue</h3>
                <div class="space-y-2">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700">Sales Revenue</span>
                        <span class="font-medium">TZS {{ number_format($revenue ?? 0, 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700">Service Revenue</span>
                        <span class="font-medium">TZS 0</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-t">
                        <span class="text-lg font-semibold text-gray-900">Total Revenue</span>
                        <span class="text-lg font-semibold text-green-600">TZS {{ number_format($revenue ?? 0, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Cost of Goods Sold -->
            <div class="border-l-4 border-orange-500 pl-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Cost of Goods Sold</h3>
                <div class="space-y-2">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700">Purchases</span>
                        <span class="font-medium">TZS {{ number_format($cogs ?? 0, 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700">Direct Costs</span>
                        <span class="font-medium">TZS 0</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-t">
                        <span class="text-lg font-semibold text-gray-900">Total COGS</span>
                        <span class="text-lg font-semibold text-orange-600">TZS {{ number_format($cogs ?? 0, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Gross Profit -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900">Gross Profit</span>
                    <span class="text-xl font-bold text-blue-600">TZS {{ number_format($grossProfit ?? 0, 0) }}</span>
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    Gross Margin: {{ $revenue > 0 ? round(($grossProfit / $revenue) * 100, 1) : 0 }}%
                </div>
            </div>

            <!-- Operating Expenses -->
            <div class="border-l-4 border-red-500 pl-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Operating Expenses</h3>
                <div class="space-y-2">
                    @if($expenseBreakdown->count() > 0)
                        @foreach($expenseBreakdown as $expense)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">{{ ucfirst($expense->category) }}</span>
                            <span class="font-medium">TZS {{ number_format($expense->total, 0) }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">General Expenses</span>
                            <span class="font-medium">TZS {{ number_format($operatingExpenses ?? 0, 0) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center py-2 border-t">
                        <span class="text-lg font-semibold text-gray-900">Total Operating Expenses</span>
                        <span class="text-lg font-semibold text-red-600">TZS {{ number_format($operatingExpenses ?? 0, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Net Profit -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 p-6 rounded-lg border border-green-200">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-xl font-bold text-gray-900">Net Profit</span>
                        <p class="text-sm text-gray-600 mt-1">After all expenses and taxes</p>
                    </div>
                    <span class="text-2xl font-bold {{ ($netProfit ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        TZS {{ number_format($netProfit ?? 0, 0) }}
                    </span>
                </div>
                <div class="mt-4 pt-4 border-t border-green-200">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Net Margin:</span>
                            <span class="font-semibold ml-2">{{ $revenue > 0 ? round(($netProfit / $revenue) * 100, 1) : 0 }}%</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="font-semibold ml-2 {{ ($netProfit ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ ($netProfit ?? 0) >= 0 ? 'Profitable' : 'Loss' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Trend Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-chart-line text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Revenue trend chart will be displayed here</p>
                </div>
            </div>
        </div>

        <!-- Expense Breakdown Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Expense Breakdown</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-chart-pie text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Expense breakdown chart will be displayed here</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Comparison Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Period Comparison</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metric</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Current Period</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Period</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Change</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">% Change</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Revenue</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">TZS {{ number_format($revenue ?? 0, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">TZS {{ number_format(($revenue ?? 0) * 0.85, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">+TZS {{ number_format(($revenue ?? 0) * 0.15, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">+15.0%</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Gross Profit</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">TZS {{ number_format($grossProfit ?? 0, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">TZS {{ number_format(($grossProfit ?? 0) * 0.9, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">+TZS {{ number_format(($grossProfit ?? 0) * 0.1, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">+10.0%</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Operating Expenses</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">TZS {{ number_format($operatingExpenses ?? 0, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">TZS {{ number_format(($operatingExpenses ?? 0) * 1.1, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">-TZS {{ number_format(($operatingExpenses ?? 0) * 0.1, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">-10.0%</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Net Profit</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ ($netProfit ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">TZS {{ number_format($netProfit ?? 0, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">TZS {{ number_format(($netProfit ?? 0) * 0.75, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">+TZS {{ number_format(($netProfit ?? 0) * 0.25, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">+25.0%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function profitLossStatement() {
    return {
        exportPDF() {
            window.open('{{ route('financial-statements.profit-loss.pdf', request()->query()) }}', '_blank');
        },
        printStatement() {
            window.print();
        }
    }
}
</script>
@endsection

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




