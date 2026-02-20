@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">System Preferences</h1>
            <p class="text-gray-600 mt-1">Configure system modules and preferences</p>
        </div>
        <a href="{{ route('settings.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Back</a>
    </div>

    <form action="{{ route('settings.system.update') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @csrf
        
        <div class="space-y-6">
            <!-- Module Settings -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Module Settings</h2>
                <div class="space-y-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="enable_pos" value="1" {{ ($settings['enable_pos'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">Enable POS System</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="enable_quotations" value="1" {{ ($settings['enable_quotations'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">Enable Quotations Module</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="enable_purchases" value="1" {{ ($settings['enable_purchases'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">Enable Purchases Module</span>
                    </label>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Notification Settings</h2>
                <div class="space-y-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="enable_notifications" value="1" {{ ($settings['enable_notifications'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">Enable Notifications</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="enable_sms" value="1" {{ ($settings['enable_sms'] ?? '0') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">Enable SMS Notifications</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="enable_email" value="1" {{ ($settings['enable_email'] ?? '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">Enable Email Notifications</span>
                    </label>
                </div>
            </div>

            <!-- Backup Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Backup Settings</h2>
                <div class="space-y-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="auto_backup" value="1" {{ ($settings['auto_backup'] ?? '0') == '1' ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">Enable Auto Backup</span>
                    </label>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Backup Frequency</label>
                        <select name="backup_frequency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="daily" {{ ($settings['backup_frequency'] ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ ($settings['backup_frequency'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ ($settings['backup_frequency'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Theme Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Appearance</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Theme</label>
                    <select name="theme" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="light" {{ ($settings['theme'] ?? 'light') == 'light' ? 'selected' : '' }}>Light</option>
                        <option value="dark" {{ ($settings['theme'] ?? '') == 'dark' ? 'selected' : '' }}>Dark</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold">
                    Save Settings
                </button>
            </div>
        </div>
    </form>
</div>
@endsection











