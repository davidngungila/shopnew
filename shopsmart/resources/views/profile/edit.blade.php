@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Profile</h1>
            <p class="text-gray-600 mt-1">Update your personal information and preferences</p>
        </div>
        <a href="{{ route('profile.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</a>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Profile Picture -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-bold" style="background-color: #009245;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="avatar" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF. Max size 2MB. Recommended: 400x400px</p>
                        <div class="mt-2 flex space-x-2">
                            <button type="button" class="text-sm text-red-600 hover:text-red-700">Remove Photo</button>
                            <button type="button" class="text-sm text-green-600 hover:text-green-700">Upload New</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', explode(' ', $user->name)[0] ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', explode(' ', $user->name)[1] ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <p class="text-xs text-gray-500 mt-1">We'll never share your email with anyone else</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">+255</span>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="712 345 678" class="w-full pl-16 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Select Gender</option>
                            <option value="male" {{ ($user->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ ($user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ ($user->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                        <select name="nationality" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Select Nationality</option>
                            <option value="tanzanian" {{ ($user->nationality ?? '') == 'tanzanian' ? 'selected' : '' }}>Tanzanian</option>
                            <option value="kenyan" {{ ($user->nationality ?? '') == 'kenyan' ? 'selected' : '' }}>Kenyan</option>
                            <option value="ugandan" {{ ($user->nationality ?? '') == 'ugandan' ? 'selected' : '' }}>Ugandan</option>
                            <option value="other" {{ ($user->nationality ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your full address">{{ old('address', $user->address) }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea name="bio" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Brief description for your profile. URLs are hyperlinked.</p>
                    </div>
                </div>
            </div>

            <!-- Shopping Preferences -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Shopping Preferences</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Language</label>
                        <select name="language" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="en" {{ ($user->language ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="sw" {{ ($user->language ?? '') == 'sw' ? 'selected' : '' }}>Swahili</option>
                            <option value="es" {{ ($user->language ?? '') == 'es' ? 'selected' : '' }}>Spanish</option>
                            <option value="fr" {{ ($user->language ?? '') == 'fr' ? 'selected' : '' }}>French</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                        <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="TZS" {{ ($user->currency ?? 'TZS') == 'TZS' ? 'selected' : '' }}>Tanzanian Shilling (TZS)</option>
                            <option value="KES" {{ ($user->currency ?? '') == 'KES' ? 'selected' : '' }}>Kenyan Shilling (KES)</option>
                            <option value="USD" {{ ($user->currency ?? '') == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                            <option value="EUR" {{ ($user->currency ?? '') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Categories</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="categories[]" value="electronics" class="mr-2 text-green-600 border-gray-300 rounded focus:ring-green-500" {{ in_array('electronics', old('categories', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">Electronics</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="categories[]" value="fashion" class="mr-2 text-green-600 border-gray-300 rounded focus:ring-green-500" {{ in_array('fashion', old('categories', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">Fashion</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="categories[]" value="home" class="mr-2 text-green-600 border-gray-300 rounded focus:ring-green-500" {{ in_array('home', old('categories', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">Home & Living</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Preference</label>
                        <select name="delivery_preference" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="standard" {{ ($user->delivery_preference ?? 'standard') == 'standard' ? 'selected' : '' }}>Standard Delivery (3-5 days)</option>
                            <option value="express" {{ ($user->delivery_preference ?? '') == 'express' ? 'selected' : '' }}>Express Delivery (1-2 days)</option>
                            <option value="same_day" {{ ($user->delivery_preference ?? '') == 'same_day' ? 'selected' : '' }}>Same Day Delivery</option>
                            <option value="pickup" {{ ($user->delivery_preference ?? '') == 'pickup' ? 'selected' : '' }}>Pickup Station</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- System Preferences -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">System Preferences</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Africa/Dar_es_Salaam" {{ ($user->timezone ?? 'Africa/Dar_es_Salaam') == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Dar es Salaam (GMT+3)</option>
                            <option value="Africa/Nairobi" {{ ($user->timezone ?? '') == 'Africa/Nairobi' ? 'selected' : '' }}>Nairobi (GMT+3)</option>
                            <option value="UTC" {{ ($user->timezone ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ ($user->timezone ?? '') == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                            <option value="Europe/London" {{ ($user->timezone ?? '') == 'Europe/London' ? 'selected' : '' }}>London</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                        <select name="date_format" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="d/m/Y" {{ ($user->date_format ?? 'd/m/Y') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                            <option value="m/d/Y" {{ ($user->date_format ?? '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                            <option value="Y-m-d" {{ ($user->date_format ?? '') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Theme</label>
                        <select name="theme" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="light" {{ ($user->theme ?? 'light') == 'light' ? 'selected' : '' }}>Light</option>
                            <option value="dark" {{ ($user->theme ?? '') == 'dark' ? 'selected' : '' }}>Dark</option>
                            <option value="auto" {{ ($user->theme ?? '') == 'auto' ? 'selected' : '' }}>Auto (System)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                        <select name="interface_language" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="en" {{ ($user->interface_language ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="sw" {{ ($user->interface_language ?? '') == 'sw' ? 'selected' : '' }}>Swahili</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Preferences</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="notifications_email" value="1" {{ ($user->notifications_email ?? true) ? 'checked' : '' }} class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="ml-3">
                                    <span class="text-sm font-medium text-gray-700">Email Notifications</span>
                                    <p class="text-xs text-gray-500">Receive order updates and promotions via email</p>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="notifications_sms" value="1" {{ ($user->notifications_sms ?? false) ? 'checked' : '' }} class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="ml-3">
                                    <span class="text-sm font-medium text-gray-700">SMS Notifications</span>
                                    <p class="text-xs text-gray-500">Get order updates via SMS</p>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="notifications_push" value="1" {{ ($user->notifications_push ?? true) ? 'checked' : '' }} class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="ml-3">
                                    <span class="text-sm font-medium text-gray-700">Push Notifications</span>
                                    <p class="text-xs text-gray-500">Browser push notifications</p>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="newsletter" value="1" {{ ($user->newsletter ?? false) ? 'checked' : '' }} class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="ml-3">
                                    <span class="text-sm font-medium text-gray-700">Newsletter</span>
                                    <p class="text-xs text-gray-500">Weekly deals and product recommendations</p>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <button type="button" class="text-red-600 hover:text-red-700 font-medium">Delete Account</button>
                <div class="space-x-3">
                    <button type="button" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold">Cancel</button>
                    <button type="submit" class="px-6 py-2 text-white rounded-lg hover:bg-green-700 font-semibold" style="background-color: #009245;">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection











