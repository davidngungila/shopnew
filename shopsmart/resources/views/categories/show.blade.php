@extends('layouts.app')

@section('title', 'Category Details - ' . $category->name)

@section('content')
<div class="space-y-6" x-data="categoryDetails()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
            <p class="text-gray-600 mt-1">Category ID: CAT-{{ str_pad($category->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('categories.edit', $category) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit Category</span>
            </a>
            <a href="{{ route('categories.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Categories</span>
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                </span>
                <span class="text-sm text-gray-500">
                    Created {{ $category->created_at->diffForHumans() }}
                </span>
            </div>
            <div class="text-sm text-gray-500">
                Last updated {{ $category->updated_at->diffForHumans() }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Category Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Category Information
                </h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category Name</dt>
                        <dd class="mt-1 text-lg text-gray-900 font-semibold">{{ $category->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded">{{ $category->slug }}</dd>
                    </div>
                    @if($category->description)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $category->description }}</dd>
                    </div>
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $category->created_at->format('M d, Y h:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $category->updated_at->format('M d, Y h:i A') }}</dd>
                        </div>
                    </div>
                </dl>
            </div>

            <!-- Hierarchy Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M7 12h10m-7 6h4"></path>
                    </svg>
                    Hierarchy Information
                </h2>
                
                <!-- Parent Category -->
                <div class="mb-4">
                    <dt class="text-sm font-medium text-gray-500 mb-2">Parent Category</dt>
                    @if($category->parent)
                    <dd class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-medium text-blue-900">{{ $category->parent->name }}</span>
                                <p class="text-sm text-blue-600">ID: CAT-{{ str_pad($category->parent->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <a href="{{ route('categories.show', $category->parent) }}" class="text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </dd>
                    @else
                    <dd class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M7 12h10m-7 6h4"></path>
                            </svg>
                            Root Category (No Parent)
                        </span>
                    </dd>
                    @endif
                </div>

                <!-- Child Categories -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-2">Child Categories ({{ $category->children->count() }})</dt>
                    @if($category->children->count() > 0)
                    <dd class="space-y-2">
                        @foreach($category->children as $child)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-green-900">{{ $child->name }}</span>
                                    <p class="text-sm text-green-600">ID: CAT-{{ str_pad($child->id, 4, '0', STR_PAD_LEFT) }} • {{ $child->products->count() }} products</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $child->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $child->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <a href="{{ route('categories.show', $child) }}" class="text-green-600 hover:text-green-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </dd>
                    @else
                    <dd class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-500">
                        No child categories
                    </dd>
                    @endif
                </div>
            </div>

            <!-- Products in this Category -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Products ({{ $category->products->count() }})
                    </h2>
                    @if($category->products->count() > 0)
                    <button @click="toggleProductsView()" class="text-sm text-purple-600 hover:text-purple-800">
                        <span x-show="showAllProducts">Show Less</span>
                        <span x-show="!showAllProducts">Show All</span>
                    </button>
                    @endif
                </div>
                
                @if($category->products->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="(product, index) in displayedProducts" :key="index">
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900" x-text="product.name"></div>
                                            <div class="text-xs text-gray-500" x-text="'ID: ' + product.id"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 font-mono" x-text="product.sku"></td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-medium" 
                                          :class="product.stock_quantity <= product.low_stock_alert ? 'text-red-600' : 'text-gray-900'"
                                          x-text="product.stock_quantity + ' units'"></span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium" x-text="'TZS ' + Number(product.selling_price).toLocaleString()"></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                          :class="product.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                          x-text="product.is_active ? 'Active' : 'Inactive'"></span>
                                </td>
                            </tr>
                            </template>
                        </tbody>
                    </table>
                    @if(!$category->products->count() <= 10)
                    <div x-show="!showAllProducts" class="mt-4 text-sm text-gray-500 text-center">
                        Showing first 10 of {{ $category->products->count() }} products
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">No products in this category yet</p>
                    <p class="text-gray-400 text-sm mt-2">Add products to see them here</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('categories.edit', $category) }}" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Category</span>
                    </a>
                    <button @click="duplicateCategory()" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span>Duplicate Category</span>
                    </button>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Delete Category</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-500">Total Products</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $category->products->count() }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-500">Active Products</dt>
                        <dd class="text-sm font-semibold text-green-600">{{ $category->products->where('is_active', true)->count() }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-500">Inactive Products</dt>
                        <dd class="text-sm font-semibold text-gray-600">{{ $category->products->where('is_active', false)->count() }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-500">Child Categories</dt>
                        <dd class="text-sm font-semibold text-purple-600">{{ $category->children->count() }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-500">Total Stock Value</dt>
                        <dd class="text-sm font-semibold text-gray-900">
                            TZS {{ number_format($category->products->sum(function($product) { return $product->stock_quantity * $product->selling_price; }), 2) }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Additional Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-500">Category Level</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            {{ $category->parent ? 'Subcategory' : 'Root Category' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Full Path</dt>
                        <dd class="text-sm text-gray-900 font-mono text-xs bg-gray-50 p-2 rounded">
                            {{ $category->getParentPath() ?? 'Root' }} / {{ $category->name }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
function categoryDetails() {
    return {
        showAllProducts: false,
        products: @json($category->products ?? []),
        
        get displayedProducts() {
            return this.showAllProducts ? this.products : this.products.slice(0, 10);
        },
        
        toggleProductsView() {
            this.showAllProducts = !this.showAllProducts;
        },
        
        duplicateCategory() {
            if (confirm('Are you sure you want to duplicate this category?')) {
                // Implementation for duplicating category
                window.location.href = '{{ url("categories") }}/{{ $category->id }}/duplicate';
            }
        }
    }
}
</script>
@endsection

