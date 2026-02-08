@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Employee</h1>
        <p class="text-gray-600 mt-1">Update employee information</p>
    </div>

    <form action="{{ route('employees.update', $employee) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User Account *</label>
                <select name="user_id" id="user_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">Select User</option>
                    @foreach($users ?? [] as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $employee->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Employee ID *</label>
                <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id', $employee->employee_id) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                @error('employee_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                <select name="role" id="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">Select Role</option>
                    @foreach($roles ?? [] as $role)
                    <option value="{{ $role }}" {{ old('role', $employee->role) == $role ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">Hire Date *</label>
                <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                @error('hire_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">Salary *</label>
                <input type="number" name="salary" id="salary" value="{{ old('salary', $employee->salary) }}" step="0.01" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                @error('salary')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" id="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('address', $employee->address) }}</textarea>
                @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }} class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Update Employee</button>
            <a href="{{ route('employees.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>
@endsection



