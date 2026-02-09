@extends('layouts.app')

@section('title', 'Employee Details')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $employee->user->name ?? 'Employee' }}</h1>
            <p class="text-gray-600 mt-1">Employee details and information</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Edit</a>
            <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Back</a>
        </div>
    </div>

    <!-- Employee Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Employee Information</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600">Employee ID</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ $employee->employee_id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">User Account</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ $employee->user->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Role</p>
                <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $employee->role ?? 'N/A' }}</span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Hire Date</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Phone</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ $employee->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Salary</p>
                <p class="text-sm font-medium text-gray-900 mt-1">TZS {{ number_format($employee->salary ?? 0, 0) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                @if($employee->is_active)
                <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                @else
                <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                @endif
            </div>
            <div class="sm:col-span-2">
                <p class="text-sm text-gray-600">Address</p>
                <p class="text-sm font-medium text-gray-900 mt-1">{{ $employee->address ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection





