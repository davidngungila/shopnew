@extends('layouts.app')

@section('title', 'Create Warehouse')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Create Warehouse</h1>
            <p class="text-gray-600 mt-1">Add a new warehouse location</p>
        </div>
        <a href="{{ route('warehouses.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Back</span>
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('warehouses.store') }}" class="space-y-6">
            @csrf

            <!-- Warehouse Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Warehouse Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="Enter warehouse name"
                >
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Address
                </label>
                <textarea 
                    id="address" 
                    name="address" 
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent @error('address') border-red-500 @enderror"
                    placeholder="Enter warehouse address (optional)"
                >{{ old('address') }}</textarea>
                @error('address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Phone Number
                </label>
                <input 
                    type="text" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent @error('phone') border-red-500 @enderror"
                    placeholder="Enter phone number (optional)"
                >
                @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="flex items-center space-x-3">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#009245] border-gray-300 rounded focus:ring-[#009245]"
                    >
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Active warehouses will be available for product assignment</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('warehouses.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2" style="background-color: #009245; color: white;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'" onfocus="this.style.outline='2px solid #009245'; this.style.outlineOffset='2px'" onblur="this.style.outline='none'">
                    Create Warehouse
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

