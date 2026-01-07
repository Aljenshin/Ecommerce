<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:500'], // Exact location for delivery
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'location' => $request->location,
            'is_admin' => false,
            'role' => User::ROLE_CUSTOMER,
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration successful! Welcome to Winbreaker!');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->hasRole(User::ROLE_HR)) {
                return redirect()->route('hr.dashboard');
            }

            if ($user->hasRole([User::ROLE_STAFF, User::ROLE_UPLOADER])) {
                return redirect()->route('staff.dashboard');
            }

            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            if ($user->hasRole(User::ROLE_HR)) {
                return redirect()->route('hr.dashboard');
            }

            if ($user->hasRole([User::ROLE_STAFF, User::ROLE_UPLOADER])) {
                return redirect()->route('staff.dashboard');
            }

            return redirect()->intended(route('home'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
