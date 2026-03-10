@extends('layouts.app')

@section('title', 'SMS Communication Logs')

@section('content')
<div class="space-y-6" x-data="smsLogs()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">SMS Communication Logs</h1>
            <p class="text-gray-600 mt-1">View and manage SMS communication logs</p>
        </div>
        <div class="flex gap-2">
            <button @click="syncFromApi()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>Sync from API
            </button>
            <button @click="exportPdf()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-file-pdf mr-2"></i>Export PDF
            </button>
            <button @click="exportExcel()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- SMS Balance Card -->
    @if($balance)
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm border border-blue-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-wallet mr-2 text-blue-600"></i>
                    SMS Balance
                </h3>
                <div class="mt-2">
                    <span class="text-3xl font-bold text-blue-600" x-text="formatNumber({{ $balance['balance'] ?? 0 }})">{{ number_format($balance['balance'] ?? 0) }}</span>
                    <span class="text-lg text-gray-600 ml-2">SMS available</span>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Last Updated</div>
                <div class="text-sm font-medium text-gray-700">{{ now()->format('Y-m-d H:i:s') }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total SMS</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-sms text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Successful</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['success']) }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Failed</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['failed']) }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Today</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['today']) }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-day text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-filter mr-2 text-indigo-600"></i>
            Filters
        </h3>
        
        <form method="GET" action="{{ route('admin.sms.logs.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From</label>
                <input type="text" name="from" value="{{ request('from') }}" 
                       placeholder="Sender name" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
                <input type="text" name="to" value="{{ request('to') }}" 
                       placeholder="Phone number" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="DELIVERED" {{ request('status') == 'DELIVERED' ? 'selected' : '' }}>Delivered</option>
                    <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                    <option value="ACCEPTED" {{ request('status') == 'ACCEPTED' ? 'selected' : '' }}>Accepted</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sent Since</label>
                <input type="datetime-local" name="sent_since" value="{{ request('sent_since') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sent Until</label>
                <input type="datetime-local" name="sent_until" value="{{ request('sent_until') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Success</label>
                <select name="success" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All</option>
                    <option value="1" {{ request('success') == '1' ? 'selected' : '' }}>Success</option>
                    <option value="0" {{ request('success') == '0' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            
            <div class="md:col-span-3 lg:col-span-6 flex items-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Apply Filters
                </button>
                <a href="{{ route('admin.sms.logs.index') }}" class="ml-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">
                            {{ $log->message_id ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ $log->from ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ $log->to }}
                        </td>
                        <td class="px-6 py-4 text-sm max-w-xs truncate">
                            {{ Str::limit($log->message ?? 'N/A', 50) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($log->status_group_name)
                                @case('DELIVERED')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Delivered
                                    </span>
                                @case('PENDING')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @case('REJECTED')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @case('ACCEPTED')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Accepted
                                    </span>
                                @default
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $log->status_group_name ?? 'Unknown' }}
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i:s') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ $log->user ? $log->user->name : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.sms.logs.show', $log->id) }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            No SMS logs found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            <a href="{{ $logs->previousPageUrl() }}" 
               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Previous
            </a>
            <a href="{{ $logs->nextPageUrl() }}" 
               class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Next
            </a>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ $logs->firstItem() }}</span>
                    to
                    <span class="font-medium">{{ $logs->lastItem() }}</span>
                    of
                    <span class="font-medium">{{ $logs->total() }}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    {{ $logs->links() }}
                </nav>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function smsLogs() {
    return {
        syncFromApi() {
            if (confirm('Sync SMS logs from API? This will fetch the latest logs from the messaging service API.')) {
                window.location.href = '{{ route("admin.sms.logs.sync") }}';
            }
        },
        
        exportPdf() {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('export', 'pdf');
            window.open(currentUrl.toString(), '_blank');
        },
        
        exportExcel() {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('export', 'excel');
            window.open(currentUrl.toString(), '_blank');
        },
        
        formatNumber(num) {
            return new Intl.NumberFormat().format(num);
        }
    }
}
</script>
@endsection
