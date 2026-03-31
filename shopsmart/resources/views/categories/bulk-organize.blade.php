@extends('layouts.app')

@section('title', 'Bulk Organize Categories')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Bulk Organize Categories</h1>
            <p class="text-gray-600 mt-1">Perform bulk operations on multiple categories</p>
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
        <h2 class="text-lg font-semibold text-blue-900 mb-3">How to Use Bulk Organize</h2>
        <ol class="list-decimal list-inside space-y-2 text-blue-800">
            <li>Select categories using the checkboxes in the table below</li>
            <li>Choose an action from the "Bulk Actions" section</li>
            <li>Confirm the action to apply it to all selected categories</li>
        </ol>
        <div class="mt-4 p-3 bg-blue-100 rounded-lg">
            <p class="text-sm text-blue-700">
                <strong>Note:</strong> Delete action cannot be undone. Make sure to backup your data before performing bulk delete operations.
            </p>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Select Categories</h3>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        Selected: <span id="selectedCount" class="font-semibold">0</span> of {{ count($categories) }}
                    </span>
                    <button onclick="selectAll()" class="text-sm text-blue-600 hover:text-blue-800">Select All</button>
                    <button onclick="clearSelection()" class="text-sm text-gray-600 hover:text-gray-800">Clear</button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" 
                                   class="category-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   value="{{ $category->id }}"
                                   onchange="updateSelectedCount()">
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
                                    <div class="text-sm text-gray-500">CAT-{{ str_pad($category->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="max-w-xs truncate">{{ $category->description ?? 'No description' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->products->count() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->parent->name ?? 'Root' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bulk Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button onclick="performBulkAction('activate')" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Activate Selected</span>
            </button>
            <button onclick="performBulkAction('deactivate')" 
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
                <span>Deactivate Selected</span>
            </button>
            <button onclick="performBulkAction('delete')" 
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Delete Selected</span>
            </button>
        </div>
    </div>
</div>

<script>
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.category-checkbox:checked');
    document.getElementById('selectedCount').textContent = checkboxes.length;
}

function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    
    categoryCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateSelectedCount();
}

function selectAll() {
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    
    categoryCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    
    selectAllCheckbox.checked = true;
    updateSelectedCount();
}

function clearSelection() {
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    
    categoryCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    selectAllCheckbox.checked = false;
    updateSelectedCount();
}

function performBulkAction(action) {
    const selectedCheckboxes = document.querySelectorAll('.category-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Categories Selected',
            text: 'Please select at least one category to perform this action.',
            confirmButtonColor: '#3B82F6'
        });
        return;
    }
    
    const categoryIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    const actionText = action === 'activate' ? 'activate' : action === 'deactivate' ? 'deactivate' : 'delete';
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Are you sure you want to ${actionText} ${selectedCheckboxes.length} categor${selectedCheckboxes.length === 1 ? 'y' : 'ies'}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'delete' ? '#EF4444' : '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: `Yes, ${actionText}!`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('action', action);
            categoryIds.forEach(id => formData.append('categories[]', id));
            
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
                        text: data.error || 'Action failed',
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
</script>
@endsection
