@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Customer</h1>
        <p class="text-gray-600 mt-1">Update customer information</p>
    </div>

    <form action="{{ route('customers.update', $customer) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent" placeholder="+255 123 456 789">
                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="flex items-center space-x-3 mt-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }} class="w-4 h-4 text-[#009245] border-gray-300 rounded focus:ring-[#009245]">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
                @error('is_active')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" id="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">{{ old('address', $customer->address) }}</textarea>
                @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('customers.show', $customer) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-white rounded-lg font-semibold transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                Update Customer
            </button>
        </div>
    </form>
</div>
@endsection


