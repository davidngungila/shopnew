@extends('layouts.app')

@section('title', 'Expense Details: ' . $expense->expense_number)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Expense Details</h1>
            <p class="text-gray-600 mt-1">Expense #{{ $expense->expense_number }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('expenses.edit', $expense) }}" class="px-4 py-2 text-white rounded-lg flex items-center space-x-2" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit Expense</span>
            </a>
            <a href="{{ route('expenses.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Expense Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Expense Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Expense Number</dt>
                        <dd class="mt-1 text-lg text-gray-900 font-semibold font-mono">{{ $expense->expense_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $expense->category }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Expense Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            {{ \Carbon\Carbon::parse($expense->expense_date)->format('F d, Y') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">
                            {{ str_replace('_', ' ', $expense->payment_method) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="mt-1 text-lg text-gray-900 font-bold text-red-600">
                            TZS {{ number_format($expense->amount, 0) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Recorded By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $expense->user->name ?? 'System' }}</dd>
                    </div>
                    @if($expense->description)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $expense->description }}</dd>
                    </div>
                    @endif
                    @if($expense->receipt)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Receipt</dt>
                        <dd class="mt-2">
                            <a href="{{ asset('storage/' . $expense->receipt) }}" target="_blank" class="inline-flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>View Receipt</span>
                            </a>
                        </dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $expense->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $expense->updated_at->format('M d, Y h:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('expenses.edit', $expense) }}" class="block w-full px-4 py-2 text-center text-white rounded-lg" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                        Edit Expense
                    </a>
                    <a href="{{ route('expenses.index') }}" class="block w-full px-4 py-2 text-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Expense Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Expense Summary</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Category</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $expense->category }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Amount</dt>
                        <dd class="text-sm font-bold text-red-600">TZS {{ number_format($expense->amount, 0) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Payment Method</dt>
                        <dd class="text-sm font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $expense->payment_method) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Date</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection


