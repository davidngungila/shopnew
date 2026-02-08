@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Roles & Permissions</h1>
            <p class="text-gray-600 mt-1">Manage user roles and access permissions</p>
        </div>
        <a href="{{ route('settings.users') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Back</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Admin Role -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">Admin</h3>
                <p class="text-sm text-gray-600 mb-4">Full system access</p>
                <ul class="text-xs text-gray-500 space-y-1">
                    <li>✓ All modules access</li>
                    <li>✓ User management</li>
                    <li>✓ Settings management</li>
                    <li>✓ Reports & Analytics</li>
                </ul>
            </div>

            <!-- Manager Role -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">Manager</h3>
                <p class="text-sm text-gray-600 mb-4">Management access</p>
                <ul class="text-xs text-gray-500 space-y-1">
                    <li>✓ Sales & POS</li>
                    <li>✓ Inventory management</li>
                    <li>✓ Reports access</li>
                    <li>✗ User management</li>
                </ul>
            </div>

            <!-- Cashier Role -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">Cashier</h3>
                <p class="text-sm text-gray-600 mb-4">Sales operations</p>
                <ul class="text-xs text-gray-500 space-y-1">
                    <li>✓ POS access</li>
                    <li>✓ Sales creation</li>
                    <li>✓ Customer management</li>
                    <li>✗ Inventory management</li>
                </ul>
            </div>

            <!-- Staff Role -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">Staff</h3>
                <p class="text-sm text-gray-600 mb-4">Limited access</p>
                <ul class="text-xs text-gray-500 space-y-1">
                    <li>✓ View products</li>
                    <li>✓ View sales</li>
                    <li>✗ Create sales</li>
                    <li>✗ Settings access</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection



