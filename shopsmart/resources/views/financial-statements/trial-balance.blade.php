@extends('layouts.app')

@section('title', 'Trial Balance')

@section('content')
<div class="space-y-6" x-data="trialBalance()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Trial Balance</h1>
            <p class="text-gray-600 mt-1">Comprehensive accounting accuracy verification</p>
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
        <form method="GET" action="{{ route('financial-statements.trial-balance') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">As of Date</label>
                    <input type="date" name="as_of_date" value="{{ $asOfDate ?? request('as_of_date', now()->toDateString()) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                    <select name="account_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">All Types</option>
                        <option value="asset">Assets</option>
                        <option value="liability">Liabilities</option>
                        <option value="equity">Equity</option>
                        <option value="revenue">Revenue</option>
                        <option value="expense">Expenses</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Category</label>
                    <select name="account_category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">All Categories</option>
                        <option value="current_asset">Current Assets</option>
                        <option value="fixed_asset">Fixed Assets</option>
                        <option value="current_liability">Current Liabilities</option>
                        <option value="long_term_liability">Long-term Liabilities</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Zero Balance</label>
                    <select name="zero_balance" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="include">Include</option>
                        <option value="exclude">Exclude</option>
                        <option value="only">Only Zero Balances</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
                <a href="{{ route('financial-statements.trial-balance') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Accounts</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $accounts->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-list text-blue-500"></i> 
                        Active accounts
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-list-alt text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Debits</p>
                    <p class="text-2xl font-bold text-green-600">TZS {{ number_format($totalDebit ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-arrow-up text-green-500"></i> 
                        Assets & Expenses
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-plus-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Credits</p>
                    <p class="text-2xl font-bold text-red-600">TZS {{ number_format($totalCredit ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-arrow-down text-red-500"></i> 
                        Liabilities & Equity
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-minus-circle text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Balance Status</p>
                    <p class="text-2xl font-bold {{ ($totalDebit == $totalCredit) ? 'text-green-600' : 'text-red-600' }}">
                        {{ ($totalDebit == $totalCredit) ? 'Balanced' : 'Unbalanced' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="{{ ($totalDebit == $totalCredit) ? 'text-green-600' : 'text-red-600' }}">
                            {{ ($totalDebit == $totalCredit) ? '✓ Verified' : '⚠ Review Required' }}
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 {{ ($totalDebit == $totalCredit) ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                    <i class="fas fa-balance-scale {{ ($totalDebit == $totalCredit) ? 'text-green-600' : 'text-red-600' }}"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Trial Balance Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Trial Balance Details</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">
                    As of: {{ \Carbon\Carbon::parse($asOfDate)->format('M d, Y') }}
                </span>
                <div class="flex items-center space-x-2">
                    <input type="text" x-model="search" placeholder="Search accounts..." class="px-3 py-1 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <button @click="exportExcel()" class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                        <i class="fas fa-file-excel mr-1"></i>Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span>Account Code</span>
                                <button @click="sortBy('code')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span>Account Name</span>
                                <button @click="sortBy('name')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span>Type</span>
                                <button @click="sortBy('type')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-end space-x-1">
                                <span>Debit</span>
                                <button @click="sortBy('debit')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-end space-x-1">
                                <span>Credit</span>
                                <button @click="sortBy('credit')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($accounts ?? [] as $account)
                    <tr class="hover:bg-gray-50 transition-colors" x-show="!search || {{ $account['name'] }}.toLowerCase().includes(search.toLowerCase())">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $account['code'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center space-x-2">
                                <span>{{ $account['name'] }}</span>
                                @if($account['type'] == 'asset')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Asset</span>
                                @elseif($account['type'] == 'liability')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Liability</span>
                                @elseif($account['type'] == 'equity')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Equity</span>
                                @elseif($account['type'] == 'revenue')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Revenue</span>
                                @elseif($account['type'] == 'expense')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Expense</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full capitalize" 
                                  :class="{
                                      'bg-blue-100 text-blue-800': '{{ $account['type'] }}' === 'asset',
                                      'bg-red-100 text-red-800': '{{ $account['type'] }}' === 'liability',
                                      'bg-green-100 text-green-800': '{{ $account['type'] }}' === 'equity',
                                      'bg-purple-100 text-purple-800': '{{ $account['type'] }}' === 'revenue',
                                      'bg-orange-100 text-orange-800': '{{ $account['type'] }}' === 'expense'
                                  }">
                                {{ $account['type'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                            @if($account['debit'] > 0)
                                TZS {{ number_format($account['debit'], 0) }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                            @if($account['credit'] > 0)
                                TZS {{ number_format($account['credit'], 0) }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Edit Account">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No accounts found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-sm font-bold text-gray-900">Totals</td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-green-600 text-right">
                            TZS {{ number_format($totalDebit ?? 0, 0) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-red-600 text-right">
                            TZS {{ number_format($totalCredit ?? 0, 0) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ ($totalDebit == $totalCredit) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ($totalDebit == $totalCredit) ? 'Balanced' : 'Difference: TZS ' . number_format(abs($totalDebit - $totalCredit), 0) }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Account Summary by Type -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Account Type Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Summary by Account Type</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-wallet text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Assets</p>
                            <p class="text-xs text-gray-500">Total debit balance</p>
                        </div>
                    </div>
                    <p class="text-lg font-bold text-blue-600">TZS {{ number_format($accounts->where('type', 'asset')->sum('debit'), 0) }}</p>
                </div>

                <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-credit-card text-red-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Liabilities</p>
                            <p class="text-xs text-gray-500">Total credit balance</p>
                        </div>
                    </div>
                    <p class="text-lg font-bold text-red-600">TZS {{ number_format($accounts->where('type', 'liability')->sum('credit'), 0) }}</p>
                </div>

                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-pie text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Equity</p>
                            <p class="text-xs text-gray-500">Total credit balance</p>
                        </div>
                    </div>
                    <p class="text-lg font-bold text-green-600">TZS {{ number_format($accounts->where('type', 'equity')->sum('credit'), 0) }}</p>
                </div>

                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Revenue</p>
                            <p class="text-xs text-gray-500">Total credit balance</p>
                        </div>
                    </div>
                    <p class="text-lg font-bold text-purple-600">TZS {{ number_format($accounts->where('type', 'revenue')->sum('credit'), 0) }}</p>
                </div>

                <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-receipt text-orange-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Expenses</p>
                            <p class="text-xs text-gray-500">Total debit balance</p>
                        </div>
                    </div>
                    <p class="text-lg font-bold text-orange-600">TZS {{ number_format($accounts->where('type', 'expense')->sum('debit'), 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Balance Verification -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Balance Verification</h3>
            <div class="space-y-4">
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Trial Balance Status</p>
                            <p class="text-xs text-gray-600 mt-1">Debits must equal credits</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold {{ ($totalDebit == $totalCredit) ? 'text-green-600' : 'text-red-600' }}">
                                {{ ($totalDebit == $totalCredit) ? '✓ Balanced' : '✗ Unbalanced' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Difference: TZS {{ number_format(abs($totalDebit - $totalCredit), 0) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Accounting Equation</p>
                            <p class="text-xs text-gray-600 mt-1">Assets = Liabilities + Equity</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">
                                {{ ($accounts->where('type', 'asset')->sum('debit') == ($accounts->where('type', 'liability')->sum('credit') + $accounts->where('type', 'equity')->sum('credit'))) ? '✓ Valid' : '✗ Invalid' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-yellow-600 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Next Steps</p>
                            <ul class="text-xs text-gray-600 mt-1 space-y-1">
                                <li>• Review unbalanced accounts if any</li>
                                <li>• Verify posting accuracy</li>
                                <li>• Check for missing entries</li>
                                <li>• Prepare adjusting entries if needed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function trialBalance() {
    return {
        search: '',
        exportPDF() {
            window.open('{{ route('financial-statements.trial-balance.pdf', request()->query()) }}', '_blank');
        },
        printStatement() {
            window.print();
        },
        exportExcel() {
            // Export to Excel functionality
            window.location.href = '{{ route('financial-statements.trial-balance') }}?export=excel';
        },
        sortBy(field) {
            // Sorting functionality
            console.log('Sorting by:', field);
        }
    }
}
</script>
@endsection




