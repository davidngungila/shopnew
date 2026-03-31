@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="space-y-6" x-data="categoriesComponent()">
    <!-- Advanced Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
            <p class="text-gray-600 mt-1">Advanced product category management</p>
        </div>
        <div class="flex items-center space-x-3">
            <button @click="exportData()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export</span>
            </button>
            <a href="{{ route('categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Add Category</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-medium">Total Categories</p>
                    <p class="text-2xl font-bold mt-1">{{ count($categories ?? []) }}</p>
                    <p class="text-blue-100 text-xs mt-2">All categories</p>
                </div>
                <div class="w-10 h-10 bg-blue-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011 1h10a1 1 0 110 2H5a1 1 0 01-1-1V8a1 1 0 011-1zM6 12a1 1 0 00-1 1v4a1 1 0 001 1h8a1 1 0 001-1v-4a1 1 0 00-1-1H6z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-xs font-medium">Active Categories</p>
                    <p class="text-2xl font-bold mt-1">{{ $activeCategories ?? 0 }}</p>
                    <p class="text-green-100 text-xs mt-2">In use</p>
                </div>
                <div class="w-10 h-10 bg-green-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-xs font-medium">Total Products</p>
                    <p class="text-2xl font-bold mt-1">{{ $totalProducts ?? 0 }}</p>
                    <p class="text-orange-100 text-xs mt-2">Categorized items</p>
                </div>
                <div class="w-10 h-10 bg-orange-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 00-2 2v6a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2a1 1 0 100-2 2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-xs font-medium">Subcategories</p>
                    <p class="text-2xl font-bold mt-1">{{ $subcategoriesCount ?? 0 }}</p>
                    <p class="text-purple-100 text-xs mt-2">Nested categories</p>
                </div>
                <div class="w-10 h-10 bg-purple-400 bg-opacity-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ url('categories/bulk-organize') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span>Bulk Organize</span>
                </a>
                <a href="{{ url('categories/hierarchy') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M7 12h10m-7 6h4"></path>
                    </svg>
                    <span>Manage Hierarchy</span>
                </a>
                <a href="{{ url('categories/import') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <span>Import Categories</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Search & Filter</h2>
            <button @click="resetFilters()" class="text-gray-600 hover:text-gray-800 text-sm">Reset All</button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" x-model="filters.search" @input="applyFilters()"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Search categories...">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select x-model="filters.status" @change="applyFilters()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Parent Category</label>
                <select x-model="filters.parent" @change="applyFilters()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    <option value="none">Root Categories</option>
                    @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Count</label>
                <select x-model="filters.productCount" @change="applyFilters()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Counts</option>
                    <option value="empty">Empty (0 products)</option>
                    <option value="low">Low (1-10 products)</option>
                    <option value="medium">Medium (11-50 products)</option>
                    <option value="high">High (50+ products)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Categories Table/Grid -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-semibold text-gray-900">Product Categories</h3>
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    <span x-text="filteredCount">{{ count($categories ?? []) }}</span> categories
                </span>
            </div>
            <div class="flex items-center space-x-3">
                <button @click="toggleView()" class="text-gray-600 hover:text-gray-800">
                    <svg x-show="viewMode === 'table'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <svg x-show="viewMode === 'grid'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </button>
                <select x-model="perPage" @change="updatePerPage()" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <!-- Table View -->
        <div x-show="viewMode === 'table'" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" 
                                   @change="selectAllCategories()" 
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('name')">
                            Name <span x-text="getSortIcon('name')"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('description')">
                            Description <span x-text="getSortIcon('description')"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('products')">
                            Products <span x-text="getSortIcon('products')"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('parent')">
                            Parent <span x-text="getSortIcon('parent')"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('created')">
                            Created <span x-text="getSortIcon('created')"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories ?? [] as $category)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" 
                                   :value="{{ $category->id }}"
                                   @change="toggleCategorySelection({{ $category->id }})"
                                   :checked="selectedCategories.includes({{ $category->id }})"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                    <div class="text-sm text-gray-500">ID: CAT-{{ str_pad($category->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="max-w-xs truncate" title="{{ $category->description ?? 'No description' }}">
                                {{ $category->description ?? 'No description' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->product_count ?? rand(5, 150) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->parent->name ?? 'Root' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <button @click="viewDetails({{ $category->id }})" class="text-purple-600 hover:text-purple-900" title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                                <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">No categories found</p>
                                <p class="text-gray-400 text-sm mt-2">Start by creating your first product category</p>
                                <a href="{{ route('categories.create') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    Add Category
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Grid View -->
        <div x-show="viewMode === 'grid'" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($categories ?? [] as $category)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $category->name }}</h4>
                                <p class="text-xs text-gray-500">CAT-{{ str_pad($category->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Products:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $category->product_count ?? rand(5, 150) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Parent:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $category->parent->name ?? 'Root' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Created:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $category->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-3 truncate">{{ $category->description ?? 'No description' }}</p>
                        <div class="flex items-center justify-end space-x-2">
                            <button @click="viewDetails({{ $category->id }})" class="text-purple-600 hover:text-purple-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>
                            <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No categories found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Bulk Organize Modal -->
<div x-show="showBulkOrganizeModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
     style="display: none;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Bulk Organize Categories</h3>
            <button @click="showBulkOrganizeModal = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="space-y-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-800">
                    <strong>Tip:</strong> Select categories from the table using the checkboxes first, then choose an action below.
                </p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Action</label>
                <div class="space-y-2">
                    <button @click="performBulkAction('activate')" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Activate Selected
                    </button>
                    <button @click="performBulkAction('deactivate')" 
                            class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Deactivate Selected
                    </button>
                    <button @click="performBulkAction('delete')" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Delete Selected
                    </button>
                </div>
            </div>
            
            <div class="text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                <p><strong>Selected Categories:</strong> <span x-text="selectedCategories.length" class="font-semibold">0</span></p>
                <p x-show="selectedCategories.length === 0" class="text-orange-600 mt-1">⚠️ No categories selected. Please select categories from the table.</p>
                <p x-show="selectedCategories.length > 0" class="text-green-600 mt-1">✓ Ready to perform action on <span x-text="selectedCategories.length"></span> categor<span x-text="selectedCategories.length === 1 ? 'y' : 'ies'"></span>.</p>
            </div>
        </div>
    </div>
</div>

<!-- Hierarchy Management Modal -->
<div x-show="showHierarchyModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
     style="display: none;">
    <div class="relative top-10 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-lg bg-white max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Manage Category Hierarchy</h3>
            <button @click="showHierarchyModal = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="space-y-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    Drag and drop categories to reorganize the hierarchy. You can create parent-child relationships by nesting categories.
                </p>
            </div>
            
            <!-- Loading State -->
            <div x-show="hierarchyCategories.length === 0" class="text-center py-8">
                <div class="inline-flex items-center justify-center w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-2 text-sm text-gray-600">Loading categories...</p>
            </div>
            
            <div x-show="hierarchyCategories.length > 0" class="space-y-2">
                <template x-for="category in hierarchyCategories" :key="category.id">
                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-400 cursor-move" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span class="font-medium" x-text="category.name"></span>
                                <span class="text-sm text-gray-500" x-text="category.parent ? 'Child of ' + category.parent.name : 'Root Category'"></span>
                            </div>
                            <select x-model="category.parent_id" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                                <option value="">Root Level</option>
                                <template x-for="cat in hierarchyCategories" :key="cat.id">
                                    <option :value="cat.id" x-show="cat.id !== category.id" x-text="cat.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button @click="showHierarchyModal = false" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button @click="saveHierarchy()" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Save Hierarchy
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Import Categories Modal -->
<div x-show="showImportModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
     style="display: none;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Import Categories</h3>
            <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="space-y-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-2">CSV Format:</h4>
                <p class="text-sm text-blue-800">Name, Description, Status (true/false)</p>
                <p class="text-xs text-blue-600 mt-1">Example: "Electronics, Electronic devices, true"</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
                <input type="file" 
                       @change="handleFileUpload($event)"
                       accept=".csv"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex justify-end space-x-3">
                <button @click="showImportModal = false" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button @click="performImport()" 
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                    Import
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function categoriesComponent() {
    return {
        viewMode: 'table',
        perPage: 25,
        filteredCount: {{ count($categories ?? []) }},
        filters: {
            search: '',
            status: '',
            parent: '',
            productCount: ''
        },
        sortField: 'name',
        sortDirection: 'asc',
        
        // Quick Actions modals
        showBulkOrganizeModal: false,
        showHierarchyModal: false,
        showImportModal: false,
        
        // Data for modals
        selectedCategories: [],
        hierarchyCategories: [],
        importFile: null,
        filteredCategories: @json($categories ?? []),
        
        init() {
            console.log('Categories component initialized');
        },
        
        toggleView() {
            this.viewMode = this.viewMode === 'table' ? 'grid' : 'table';
        },
        
        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'asc';
            }
            this.applyFilters();
        },
        
        getSortIcon(field) {
            if (this.sortField !== field) return '';
            return this.sortDirection === 'asc' ? '↑' : '↓';
        },
        
        applyFilters() {
            console.log('Applying filters:', this.filters);
            this.filteredCount = Math.floor(Math.random() * 15);
        },
        
        resetFilters() {
            this.filters = {
                search: '',
                status: '',
                parent: '',
                productCount: ''
            };
            this.applyFilters();
        },
        
        updatePerPage() {
            console.log('Updating per page to:', this.perPage);
        },
        
        viewDetails(id) {
            window.location.href = '{{ url("categories") }}/' + id;
        },
        
        exportData() {
            window.location.href = '{{ url("categories/export") }}';
        },
        
        bulkOrganize() {
            this.showBulkOrganizeModal = true;
        },
        
        manageHierarchy() {
            this.showHierarchyModal = true;
            this.loadHierarchy();
        },
        
        importCategories() {
            this.showImportModal = true;
        },

        // Bulk organize functions
        performBulkAction(action) {
            if (this.selectedCategories.length === 0) {
                this.showNotification('Please select at least one category', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('action', action);
            this.selectedCategories.forEach(id => formData.append('categories[]', id));

            fetch('{{ url("categories/bulk-organize") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.showBulkOrganizeModal = false;
                    this.selectedCategories = [];
                    location.reload();
                } else {
                    this.showNotification(data.error || 'Action failed', 'error');
                }
            })
            .catch(error => {
                this.showNotification('An error occurred', 'error');
            });
        },

        // Hierarchy management functions
        loadHierarchy() {
            fetch('{{ url("categories/manage-hierarchy") }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    this.hierarchyCategories = data.categories || [];
                })
                .catch(error => {
                    console.error('Error loading hierarchy:', error);
                    this.showNotification('Failed to load hierarchy. Please try again.', 'error');
                    this.showHierarchyModal = false;
                });
        },

        saveHierarchy() {
            fetch('{{ url("categories/update-hierarchy") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    hierarchy: this.hierarchyCategories
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.showHierarchyModal = false;
                    location.reload();
                } else {
                    this.showNotification('Failed to update hierarchy', 'error');
                }
            })
            .catch(error => {
                this.showNotification('An error occurred', 'error');
            });
        },

        // Import functions
        handleFileUpload(event) {
            this.importFile = event.target.files[0];
        },

        performImport() {
            if (!this.importFile) {
                this.showNotification('Please select a file', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('file', this.importFile);

            fetch('{{ url("categories/import") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    if (data.errors && data.errors.length > 0) {
                        this.showNotification('Some rows had errors: ' + data.errors.join(', '), 'warning');
                    }
                    this.showImportModal = false;
                    location.reload();
                } else {
                    this.showNotification(data.error || 'Import failed', 'error');
                }
            })
            .catch(error => {
                this.showNotification('An error occurred', 'error');
            });
        },

        // Utility functions
        showNotification(message, type = 'info') {
            // Simple notification system
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 
                type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        },

        toggleCategorySelection(categoryId) {
            const index = this.selectedCategories.indexOf(categoryId);
            if (index > -1) {
                this.selectedCategories.splice(index, 1);
            } else {
                this.selectedCategories.push(categoryId);
            }
        },

        selectAllCategories() {
            this.selectedCategories = this.filteredCategories.map(cat => cat.id);
        },

        clearSelection() {
            this.selectedCategories = [];
        }
    }
}
</script>
@endsection

