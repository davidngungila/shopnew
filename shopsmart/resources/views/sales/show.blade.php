@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Sale Details</h1>
            <p class="text-gray-600 mt-1">Invoice #{{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('sales.print', $sale) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Print Receipt</span>
            </a>
            <a href="{{ route('sales.pdf', $sale) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Download PDF</span>
            </a>
            <a href="{{ route('sales.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Invoice Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Invoice Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Invoice Number</dt>
                        <dd class="mt-1 text-lg text-gray-900 font-semibold">#{{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}<br>
                            <span class="text-gray-500">{{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('h:i A') }} EAT</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Customer</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $sale->customer->name ?? 'Walk-in Customer' }}</dd>
                        @if($sale->customer)
                        <dd class="text-xs text-gray-500">{{ $sale->customer->email ?? $sale->customer->phone ?? '' }}</dd>
                        @endif
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                {{ str_replace('_', ' ', $sale->payment_method ?? 'cash') }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Cashier</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $sale->user->name ?? 'System' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Items -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Items</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sale->items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name ?? 'Item' }}</div>
                                    @if($item->product && $item->product->sku)
                                    <div class="text-xs text-gray-500">SKU: {{ $item->product->sku }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">{{ number_format($item->quantity) }}</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">TZS {{ number_format($item->unit_price, 0) }}</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">TZS {{ number_format($item->total, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Summary</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-600">Subtotal</dt>
                        <dd class="text-sm font-semibold text-gray-900">TZS {{ number_format($sale->subtotal, 0) }}</dd>
                    </div>
                    @if($sale->discount > 0)
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-600">Discount</dt>
                        <dd class="text-sm font-semibold text-red-600">- TZS {{ number_format($sale->discount, 0) }}</dd>
                    </div>
                    @endif
                    @if($sale->tax > 0)
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-600">Tax (VAT)</dt>
                        <dd class="text-sm font-semibold text-gray-900">TZS {{ number_format($sale->tax, 0) }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <dt class="text-base font-bold text-gray-900">Total</dt>
                        <dd class="text-lg font-bold" style="color: #009245;">TZS {{ number_format($sale->total, 0) }}</dd>
                    </div>
                </dl>
            </div>

            @php
                $sale->load('payments');
                $totalPaid = $sale->payments->sum('amount');
                $balance = $sale->total - $totalPaid;
            @endphp
            
            @if($sale->payment_method === 'credit')
            <!-- Payment Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Status</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-600">Total Amount</dt>
                        <dd class="text-sm font-semibold text-gray-900">TZS {{ number_format($sale->total, 0) }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-600">Total Paid</dt>
                        <dd class="text-sm font-semibold text-green-600">TZS {{ number_format($totalPaid, 0) }}</dd>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <dt class="text-base font-bold text-gray-900">Balance</dt>
                        <dd class="text-lg font-bold {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }}">TZS {{ number_format($balance, 0) }}</dd>
                    </div>
                    @if($balance > 0)
                    <div class="mt-4">
                        <button onclick="openPaymentModal()" class="w-full flex items-center justify-center space-x-2 px-4 py-2 text-white rounded-lg transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Record Payment</span>
                        </button>
                    </div>
                    @else
                    <div class="mt-4 p-3 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-800 font-medium text-center">✓ Fully Paid</p>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Payment History -->
            @if($sale->payments->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment History</h3>
                <div class="space-y-3">
                    @foreach($sale->payments->sortByDesc('payment_date') as $payment)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="text-sm font-medium text-gray-900">TZS {{ number_format($payment->amount, 0) }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $payment->payment_date->format('M d, Y') }} • 
                                <span class="capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                                @if($payment->user)
                                • {{ $payment->user->name }}
                                @endif
                            </div>
                            @if($payment->notes)
                            <div class="text-xs text-gray-400 mt-1">{{ $payment->notes }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('sales.print', $sale) }}" target="_blank" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span>Print Receipt</span>
                    </a>
                    <a href="{{ route('sales.pdf', $sale) }}" target="_blank" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Download PDF</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 z-50 overflow-y-auto hidden" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Record Payment</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="paymentForm" onsubmit="submitPayment(event)">
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-semibold">TZS {{ number_format($sale->total, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Total Paid:</span>
                            <span class="font-semibold">TZS {{ number_format($totalPaid, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold border-t border-gray-200 pt-2">
                            <span>Balance:</span>
                            <span class="text-red-600">TZS {{ number_format($balance, 0) }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Amount (TZS)</label>
                        <input type="number" id="paymentAmount" name="amount" step="0.01" min="0.01" max="{{ $balance }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select id="paymentMethodSelect" name="payment_method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                        <input type="date" id="paymentDate" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <textarea id="paymentNotes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePaymentModal()" class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm text-white rounded-lg transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function openPaymentModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('paymentAmount').focus();
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('paymentForm').reset();
    }

    function submitPayment(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = {
            amount: parseFloat(formData.get('amount')),
            payment_method: formData.get('payment_method'),
            payment_date: formData.get('payment_date'),
            notes: formData.get('notes')
        };

        fetch('{{ route('sales.record-payment', $sale) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Payment recorded successfully!');
                closePaymentModal();
                location.reload();
            } else {
                alert(result.message || 'Error recording payment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error recording payment: ' + error.message);
        });
    }

    // Close modal on background click
    document.getElementById('paymentModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePaymentModal();
        }
    });
</script>
@endsection



