@extends('layouts.app')

@section('title', 'SMS Log Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">SMS Log Details</h1>
            <p class="text-gray-600 mt-1">View detailed information about this SMS message</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.sms.logs.index') }}" class="px-4 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors" style="background-color: #6b7280;">
                <i class="fas fa-arrow-left mr-2"></i>Back to Logs
            </a>
            <button @click="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-print mr-2"></i>Print
            </button>
        </div>
    </div>

    <!-- SMS Details Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6">
            <!-- Status Badge -->
            <div class="mb-6">
                @switch($smsLog->status_group_name)
                    @case('DELIVERED')
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>Delivered Successfully
                        </span>
                    @case('PENDING')
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-2"></i>Pending Delivery
                        </span>
                    @case('REJECTED')
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-2"></i>Message Rejected
                        </span>
                    @case('ACCEPTED')
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            <i class="fas fa-check mr-2"></i>Message Accepted
                        </span>
                    @default
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            <i class="fas fa-question-circle mr-2"></i>{{ $smsLog->status_group_name ?? 'Unknown Status' }}
                        </span>
                @endswitch
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message ID</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <code class="text-sm text-gray-900 font-mono">{{ $smsLog->message_id ?? 'N/A' }}</code>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <code class="text-sm text-gray-900 font-mono">{{ $smsLog->reference ?? 'N/A' }}</code>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From (Sender)</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->from ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To (Recipient)</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->to }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Channel</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->channel ?? 'Internet SMS' }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SMS Count</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->sms_count ?? 1 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Group</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->status_group_name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Name</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->status_name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Description</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 text-sm">{{ $smsLog->status_description ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sent At</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->sent_at ? $smsLog->sent_at->format('Y-m-d H:i:s') : 'N/A' }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Done At</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->done_at ? $smsLog->done_at->format('Y-m-d H:i:s') : 'N/A' }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Success</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            @if($smsLog->success)
                                <span class="text-green-600 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>Yes
                                </span>
                            @else
                                <span class="text-red-600 font-medium">
                                    <i class="fas fa-times-circle mr-1"></i>No
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Message Content</label>
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $smsLog->message ?? 'N/A' }}</p>
                </div>
                <div class="mt-2 text-sm text-gray-500">
                    Character Count: {{ strlen($smsLog->message ?? '') }} / 160
                </div>
            </div>

            <!-- User Information -->
            @if($smsLog->user)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    User Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">User Name</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->user->name }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">User Email</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->user->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Sent By Information -->
            @if($smsLog->sentByUser)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user-cog mr-2 text-green-600"></i>
                    Sent By
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sent By Name</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->sentByUser->name }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sent By Email</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->sentByUser->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- API Response -->
            @if($smsLog->api_response)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-code mr-2 text-purple-600"></i>
                    API Response
                </h3>
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                    <pre class="text-sm text-gray-900 overflow-x-auto"><code>{{ json_encode($smsLog->api_response, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
            @endif

            <!-- Delivery Information -->
            @if($smsLog->delivery)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-truck mr-2 text-indigo-600"></i>
                    Delivery Information
                </h3>
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                    <pre class="text-sm text-gray-900 overflow-x-auto"><code>{{ json_encode($smsLog->delivery, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
            @endif

            <!-- Error Information -->
            @if(!$smsLog->success && $smsLog->error_message)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                    Error Information
                </h3>
                <div class="p-4 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-red-800 font-medium">{{ $smsLog->error_message }}</p>
                </div>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-clock mr-2 text-orange-600"></i>
                    Timestamps
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created At</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->created_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Updated At</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">{{ $smsLog->updated_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <span class="text-gray-900 font-medium">
                                @if($smsLog->sent_at && $smsLog->done_at)
                                    {{ $smsLog->sent_at->diffInSeconds($smsLog->done_at) }} seconds
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
