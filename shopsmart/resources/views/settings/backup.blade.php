@extends('layouts.app')

@section('title', 'Backup & Maintenance')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Backup & Maintenance</h1>
            <p class="text-gray-600 mt-1">Manage backups and system maintenance</p>
        </div>
        <a href="{{ route('settings.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Database Backup -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Database Backup</h2>
            <p class="text-sm text-gray-600 mb-4">Create a backup of your database</p>
            <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                Create Backup Now
            </button>
        </div>

        <!-- System Logs -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">System Logs</h2>
            <p class="text-sm text-gray-600 mb-4">View and manage system logs</p>
            <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-block">
                View Logs
            </a>
        </div>

        <!-- Clear Cache -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Clear Cache</h2>
            <p class="text-sm text-gray-600 mb-4">Clear application cache and temporary files</p>
            <form action="#" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700" onclick="return confirm('Clear all cache?')">
                    Clear Cache
                </button>
            </form>
        </div>

        <!-- System Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">System Information</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">PHP Version:</span>
                    <span class="font-medium">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Laravel Version:</span>
                    <span class="font-medium">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Database:</span>
                    <span class="font-medium">MySQL</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




