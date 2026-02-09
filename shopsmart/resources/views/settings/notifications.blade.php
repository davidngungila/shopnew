@extends('layouts.app')

@section('title', 'Notification Settings')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Notification Settings</h1>
            <p class="text-gray-600 mt-1">Configure alerts and reminders</p>
        </div>
        <a href="{{ route('settings.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Back</a>
    </div>

    <form action="{{ route('settings.notifications.update') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @csrf
        
        <div class="space-y-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Alert Preferences</h2>
            <div class="space-y-4">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="low_stock_alert" value="1" {{ ($settings['low_stock_alert'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Low Stock Alerts</span>
                        <p class="text-xs text-gray-500">Get notified when products reach low stock levels</p>
                    </div>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="quotation_reminder" value="1" {{ ($settings['quotation_reminder'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Pending Quotation Reminders</span>
                        <p class="text-xs text-gray-500">Remind about pending quotations</p>
                    </div>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="invoice_overdue_alert" value="1" {{ ($settings['invoice_overdue_alert'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Overdue Invoice Alerts</span>
                        <p class="text-xs text-gray-500">Alert when invoices become overdue</p>
                    </div>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="payment_received_alert" value="1" {{ ($settings['payment_received_alert'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Payment Received Alerts</span>
                        <p class="text-xs text-gray-500">Notify when payments are received</p>
                    </div>
                </label>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold">
                    Save Settings
                </button>
            </div>
        </div>
    </form>
</div>
@endsection




