@extends('layouts.app')

@section('title', 'Trial Balance')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Trial Balance</h1>
            <p class="text-gray-600 mt-1">Account balances summary</p>
        </div>
        <div class="flex gap-2">
            <input type="date" name="as_of_date" value="{{ $asOfDate ?? now()->toDateString() }}" onchange="window.location.href='{{ route('financial-statements.trial-balance') }}?as_of_date='+this.value" class="px-4 py-2 border border-gray-300 rounded-lg">
            <a href="{{ route('financial-statements.trial-balance.pdf', request()->query()) }}" target="_blank" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export PDF</span>
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Print</button>
        </div>
    </div>

    <!-- Trial Balance Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Debit</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Credit</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($accounts ?? [] as $account)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $account['code'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $account['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">{{ $account['type'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                            @if($account['debit'] > 0)
                            TSh {{ number_format($account['debit'], 0) }}
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                            @if($account['credit'] > 0)
                            TSh {{ number_format($account['credit'], 0) }}
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No accounts found</td>
                    </tr>
                    @endforelse
                    @if(isset($accounts) && $accounts->count() > 0)
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="3" class="px-6 py-4 text-sm text-gray-900">Total</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">TSh {{ number_format($totalDebit ?? 0, 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">TSh {{ number_format($totalCredit ?? 0, 0) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection




