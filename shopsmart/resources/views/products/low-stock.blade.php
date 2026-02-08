@extends('layouts.app')

@section('title', 'Low Stock Alerts')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Low Stock Alerts</h1>
            <p class="text-gray-600 mt-1">Products below minimum stock level</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alert Level</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products ?? [] as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">{{ $product->stock_quantity }} {{ $product->unit }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->low_stock_alert }} {{ $product->unit }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('products.edit', $product) }}" class="text-purple-600 hover:text-purple-900">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No low stock items</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection



