<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user() ?? auth()->user();
        
        if (!$user) {
            // If no authenticated user, try to get the first user as fallback
            $user = \App\Models\User::first();
            
            if (!$user) {
                // If no users exist at all, redirect to dashboard with error
                return redirect()->route('dashboard')->with('error', 'No user found. Please log in or create a user account.');
            }
        }
        
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user() ?? auth()->user();
        
        if (!$user) {
            $user = \App\Models\User::first();
            if (!$user) {
                return redirect()->route('dashboard')->with('error', 'No user found. Please log in or create a user account.');
            }
        }
        
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user() ?? auth()->user();
        
        if (!$user) {
            $user = \App\Models\User::first();
            if (!$user) {
                return redirect()->route('dashboard')->with('error', 'No user found. Please log in or create a user account.');
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:1000',
            'language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'date_format' => 'nullable|string|max:20',
            'notifications_email' => 'nullable|boolean',
            'notifications_sms' => 'nullable|boolean',
            'theme' => 'nullable|in:light,dark',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user() ?? auth()->user();
        
        if (!$user) {
            $user = \App\Models\User::first();
            if (!$user) {
                return redirect()->route('dashboard')->with('error', 'No user found. Please log in or create a user account.');
            }
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user() ?? auth()->user();
        
        if (!$user) {
            $user = \App\Models\User::first();
            if (!$user) {
                return redirect()->route('dashboard')->with('error', 'No user found. Please log in or create a user account.');
            }
        }

        $validated = $request->validate([
            'language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'date_format' => 'nullable|string|max:20',
            'notifications_email' => 'nullable|boolean',
            'notifications_sms' => 'nullable|boolean',
            'theme' => 'nullable|in:light,dark',
        ]);

        $user->update($validated);

        return back()->with('success', 'Preferences updated successfully.');
    }

    public function activity()
    {
        $user = Auth::user() ?? auth()->user();
        
        if (!$user) {
            $user = \App\Models\User::first();
            if (!$user) {
                return redirect()->route('dashboard')->with('error', 'No user found. Please log in or create a user account.');
            }
        }
        
        // Get user activity logs
        $activities = []; // Implement activity logging
        return view('profile.activity', compact('user', 'activities'));
    }

    public function security()
    {
        $user = Auth::user() ?? auth()->user();
        
        if (!$user) {
            $user = \App\Models\User::first();
            if (!$user) {
                return redirect()->route('dashboard')->with('error', 'No user found. Please log in or create a user account.');
            }
        }
        
        return view('profile.security', compact('user'));
    }
}
