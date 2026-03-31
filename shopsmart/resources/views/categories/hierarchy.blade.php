@extends('layouts.app')

@section('title', 'Manage Category Hierarchy')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manage Category Hierarchy</h1>
            <p class="text-gray-600 mt-1">Organize categories into parent-child relationships</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('categories.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Categories</span>
            </a>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-blue-900 mb-3">How to Manage Hierarchy</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="font-medium text-blue-800 mb-2">📁 Creating Parent-Child Relationships</h3>
                <ul class="list-disc list-inside space-y-1 text-blue-700 text-sm">
                    <li>Select a parent category from the dropdown</li>
                    <li>Categories with "Root Level" have no parent</li>
                    <li>Child categories inherit from their parent</li>
                </ul>
            </div>
            <div>
                <h3 class="font-medium text-blue-800 mb-2">🔄 Best Practices</h3>
                <ul class="list-disc list-inside space-y-1 text-blue-700 text-sm">
                    <li>Keep hierarchy levels shallow (2-3 levels max)</li>
                    <li>Use descriptive category names</li>
                    <li>Test hierarchy changes before saving</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Hierarchy Editor -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Category Hierarchy Editor</h3>
            <div class="flex items-center space-x-3">
                <button onclick="resetHierarchy()" class="text-sm text-gray-600 hover:text-gray-800">Reset Changes</button>
                <button onclick="saveHierarchy()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Save Hierarchy
                </button>
            </div>
        </div>

        <div class="space-y-4">
            @foreach($categories as $category)
            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $category->name }}</h4>
                            <p class="text-sm text-gray-500">CAT-{{ str_pad($category->id, 4, '0', STR_PAD_LEFT) }} • {{ $category->products->count() }} products</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($category->children->count() > 0)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $category->children->count() }} children
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <label class="text-sm font-medium text-gray-700">Parent:</label>
                        <select name="parent_{{ $category->id }}" class="parent-select px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" data-category-id="{{ $category->id }}">
                            <option value="">Root Level</option>
                            @foreach($categories as $parentOption)
                                @if($parentOption->id !== $category->id)
                                <option value="{{ $parentOption->id }}" {{ $category->parent_id === $parentOption->id ? 'selected' : '' }}>
                                    {{ $parentOption->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                
                @if($category->children->count() > 0)
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-2">Child categories:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($category->children as $child)
                        <span class="inline-flex items-center px-2 py-1 text-xs bg-green-50 text-green-700 rounded-full">
                            {{ $child->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Hierarchy Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Categories</p>
                    <p class="text-2xl font-bold text-gray-900">{{ count($categories) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M7 12h10m-7 6h4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Root Categories</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $categories->where('parent_id', null)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Subcategories</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $categories->where('parent_id', '!=', null)->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function saveHierarchy() {
    const hierarchy = [];
    const parentSelects = document.querySelectorAll('.parent-select');
    
    parentSelects.forEach(select => {
        const categoryId = select.dataset.categoryId;
        const parentId = select.value || null;
        
        hierarchy.push({
            id: parseInt(categoryId),
            parent_id: parentId ? parseInt(parentId) : null
        });
    });
    
    Swal.fire({
        title: 'Are you sure?',
        text: 'Are you sure you want to save these hierarchy changes?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, save changes!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ url("categories/update-hierarchy") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    hierarchy: hierarchy
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        confirmButtonColor: '#10B981'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update hierarchy',
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred',
                    confirmButtonColor: '#EF4444'
                });
            });
        }
    });
}

function resetHierarchy() {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Are you sure you want to reset all changes?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#F59E0B',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, reset!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            location.reload();
        }
    });
}

// Prevent circular references
document.querySelectorAll('.parent-select').forEach(select => {
    select.addEventListener('change', function() {
        const categoryId = this.dataset.categoryId;
        const selectedParentId = this.value;
        
        // Check if this would create a circular reference
        if (selectedParentId) {
            const parentSelects = document.querySelectorAll('.parent-select');
            parentSelects.forEach(otherSelect => {
                if (otherSelect.dataset.categoryId === selectedParentId) {
                    // Check if the selected parent has this category as a parent (directly or indirectly)
                    const currentParent = otherSelect.value;
                    if (currentParent === categoryId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Circular Reference Detected!',
                            text: 'This would create an invalid hierarchy.',
                            confirmButtonColor: '#F59E0B'
                        });
                        this.value = this.dataset.originalValue || '';
                        return;
                    }
                }
            });
        }
        
        // Store original value for reset
        this.dataset.originalValue = this.value;
    });
    
    // Store initial value
    select.dataset.originalValue = select.value;
});
</script>
@endsection
