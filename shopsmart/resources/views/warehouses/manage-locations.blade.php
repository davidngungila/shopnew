@extends('layouts.app')

@section('title', 'Manage Warehouse Locations')

@section('content')
<div class="space-y-6" x-data="manageLocationsComponent()">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manage Locations</h1>
            <p class="text-gray-600 mt-1">Organize and manage warehouse locations and capacity</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('warehouses.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Warehouses</span>
            </a>
        </div>
    </div>

    <!-- Warehouse Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($warehouses as $warehouse)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ $warehouse->name }}</h3>
                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $warehouse->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Products:</span>
                    <span class="font-medium text-gray-900">{{ $warehouse->products->count() }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Stock:</span>
                    <span class="font-medium text-gray-900">{{ number_format($warehouse->products->sum('stock_quantity')) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Capacity:</span>
                    <span class="font-medium text-gray-900">{{ $warehouse->capacity ? number_format($warehouse->capacity) : 'Unlimited' }}</span>
                </div>
                @if($warehouse->capacity)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Utilization:</span>
                    <span class="font-medium {{ ($warehouse->products->sum('stock_quantity') / $warehouse->capacity) * 100 >= 90 ? 'text-red-600' : (($warehouse->products->sum('stock_quantity') / $warehouse->capacity) * 100 >= 75 ? 'text-orange-600' : 'text-green-600') }}">
                        {{ number_format(($warehouse->products->sum('stock_quantity') / $warehouse->capacity) * 100, 1) }}%
                    </span>
                </div>
                @endif
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">Quick Actions</h4>
                    <button onclick="toggleActions({{ $warehouse->id }})" class="text-sm text-blue-600 hover:text-blue-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="actions-{{ $warehouse->id }}" class="hidden space-y-2">
                    <button onclick="addLocation({{ $warehouse->id }}, '{{ $warehouse->name }}')" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm transition-colors">
                        Add Location
                    </button>
                    <button onclick="updateCapacity({{ $warehouse->id }}, '{{ $warehouse->name }}', {{ $warehouse->capacity ?? 0 }})" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm transition-colors">
                        Update Capacity
                    </button>
                    <button onclick="viewLocations({{ $warehouse->id }}, '{{ $warehouse->name }}')" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-sm transition-colors">
                        View Locations
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Location Management -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Location Management</h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Add New Location -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">Add New Location</h4>
                <form id="addLocationForm" class="space-y-3">
                    <input type="hidden" name="action" value="add_location">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Warehouse</label>
                        <select name="warehouse_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select warehouse</option>
                            @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location Name</label>
                        <input type="text" name="location_name" required placeholder="e.g., Aisle 1, Rack B, Section 3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location Type</label>
                        <select name="location_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="aisle">Aisle</option>
                            <option value="rack">Rack</option>
                            <option value="shelf">Shelf</option>
                            <option value="bin">Bin</option>
                            <option value="section">Section</option>
                            <option value="zone">Zone</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Capacity (Optional)</label>
                        <input type="number" name="location_capacity" placeholder="Maximum units" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Add Location
                    </button>
                </form>
            </div>

            <!-- Update Warehouse Capacity -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">Update Warehouse Capacity</h4>
                <form id="updateCapacityForm" class="space-y-3">
                    <input type="hidden" name="action" value="update_capacity">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Warehouse</label>
                        <select name="warehouse_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select warehouse</option>
                            @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $warehouse->capacity ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Capacity</label>
                        <input type="number" name="capacity" required placeholder="Maximum units" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-sm text-blue-800">
                            <strong>Note:</strong> Setting capacity to 0 will make it unlimited. Current utilization will be calculated automatically.
                        </p>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Update Capacity
        </div>
    </div>

    <!-- Existing Locations -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Existing Locations</h3>
        
        <div class="space-y-4">
            @foreach($warehouses as $warehouse)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-900">{{ $warehouse->name }}</h4>
                    <span class="text-sm text-gray-500">{{ $warehouse->products->count() }} products</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <!-- Sample locations - in a real app, these would come from a database -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">Aisle 1</span>
                            <button onclick="removeLocation({{ $warehouse->id }}, 'Aisle 1')" class="text-red-600 hover:text-red-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <span class="text-xs text-gray-500">Aisle</span>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">Rack A</span>
                            <button onclick="removeLocation({{ $warehouse->id }}, 'Rack A')" class="text-red-600 hover:text-red-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <span class="text-xs text-gray-500">Rack</span>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">Section B</span>
                            <button onclick="removeLocation({{ $warehouse->id }}, 'Section B')" class="text-red-600 hover:text-red-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <span class="text-xs text-gray-500">Section</span>
                    </div>
                </div>
                
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <button onclick="viewAllLocations({{ $warehouse->id }})" class="text-sm text-blue-600 hover:text-blue-800">
                        View all locations for {{ $warehouse->name }}
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function toggleActions(warehouseId) {
    const actionsDiv = document.getElementById(`actions-${warehouseId}`);
    actionsDiv.classList.toggle('hidden');
}

function addLocation(warehouseId, warehouseName) {
    Swal.fire({
        title: 'Add Location',
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location Name</label>
                    <input type="text" id="locationName" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="e.g., Aisle 1, Rack B">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location Type</label>
                    <select id="locationType" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="aisle">Aisle</option>
                        <option value="rack">Rack</option>
                        <option value="shelf">Shelf</option>
                        <option value="bin">Bin</option>
                        <option value="section">Section</option>
                        <option value="zone">Zone</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity (Optional)</label>
                    <input type="number" id="locationCapacity" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Maximum units">
                </div>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Add Location',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const name = document.getElementById('locationName').value;
            const type = document.getElementById('locationType').value;
            const capacity = document.getElementById('locationCapacity').value;
            
            if (!name) {
                Swal.showValidationMessage('Please enter a location name');
                return false;
            }
            
            return { name, type, capacity };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('warehouse_id', warehouseId);
            formData.append('action', 'add_location');
            formData.append('location_name', result.value.name);
            
            fetch('{{ route("warehouses.manage-locations.post") }}', {
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
                        text: data.error || 'Failed to add location',
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

function updateCapacity(warehouseId, warehouseName, currentCapacity) {
    Swal.fire({
        title: 'Update Warehouse Capacity',
        html: `
            <div class="text-left">
                <p class="text-sm text-gray-600 mb-4">Update capacity for <strong>${warehouseName}</strong></p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Capacity</label>
                    <input type="number" id="newCapacity" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="${currentCapacity}" placeholder="Maximum units (0 for unlimited)">
                </div>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Update Capacity',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const capacity = document.getElementById('newCapacity').value;
            if (capacity < 0) {
                Swal.showValidationMessage('Capacity must be 0 or greater');
                return false;
            }
            return capacity;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('warehouse_id', warehouseId);
            formData.append('action', 'update_capacity');
            formData.append('capacity', result.value);
            
            fetch('{{ route("warehouses.manage-locations.post") }}', {
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
                        text: data.error || 'Failed to update capacity',
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

function removeLocation(warehouseId, locationName) {
    Swal.fire({
        title: 'Remove Location',
        text: `Are you sure you want to remove "${locationName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Remove',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('warehouse_id', warehouseId);
            formData.append('action', 'remove_location');
            formData.append('location_id', 1); // This would be the actual location ID
            
            fetch('{{ route("warehouses.manage-locations.post") }}', {
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
                        text: data.error || 'Failed to remove location',
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

function viewLocations(warehouseId, warehouseName) {
    Swal.fire({
        title: `Locations for ${warehouseName}`,
        html: `
            <div class="text-left">
                <p class="text-sm text-gray-600 mb-4">This would show a detailed list of all locations in ${warehouseName}</p>
                <div class="space-y-2">
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="font-medium">Aisle 1</div>
                        <div class="text-sm text-gray-600">Aisle - 500 units capacity</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="font-medium">Rack A</div>
                        <div class="text-sm text-gray-600">Rack - 200 units capacity</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="font-medium">Section B</div>
                        <div class="text-sm text-gray-600">Section - 1000 units capacity</div>
                    </div>
                </div>
            </div>
        `,
        icon: 'info',
        confirmButtonColor: '#3B82F6',
        confirmButtonText: 'Close'
    });
}

function viewAllLocations(warehouseId) {
    // This would navigate to a detailed locations page
    console.log('View all locations for warehouse:', warehouseId);
}

// Form submissions
document.getElementById('addLocationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('{{ route("warehouses.manage-locations.post") }}', {
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
                text: data.error || 'Failed to add location',
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
});

document.getElementById('updateCapacityForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('{{ route("warehouses.manage-locations.post") }}', {
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
                text: data.error || 'Failed to update capacity',
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
});

function manageLocationsComponent() {
    return {
        selectedWarehouse: '',
        action: 'update_capacity',
        capacity: '',
        
        init() {
            // Initialize component
            console.log('Manage locations component initialized');
            console.log('Warehouse data:', @json($warehouses));
        },
        
        deleteLocation(locationId, locationName) {
            Swal.fire({
                title: 'Delete Location',
                html: `Are you sure you want to delete <strong>${locationName}</strong>?<br><br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/warehouses/manage-locations`;
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add DELETE method
                    const methodToken = document.createElement('input');
                    methodToken.type = 'hidden';
                    methodToken.name = '_method';
                    methodToken.value = 'DELETE';
                    form.appendChild(methodToken);
                    
                    // Add location ID
                    const locationIdInput = document.createElement('input');
                    locationIdInput.type = 'hidden';
                    locationIdInput.name = 'location_id';
                    locationIdInput.value = locationId;
                    form.appendChild(locationIdInput);
                    
                    // Add action field
                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = 'remove_location';
                    form.appendChild(actionInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    }
}
</script>
@endsection
