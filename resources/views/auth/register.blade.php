@extends('layouts.app')

@section('title', 'Register - Uni-H-Pen')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
    <h2 class="text-3xl font-bold mb-6 text-center">Create Your Account</h2>
    <p class="text-center text-gray-600 mb-6">Join Uni-H-Pen and start shopping! Fill in your details below.</p>
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Account Information</h3>
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    placeholder="John Doe">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address *</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                    placeholder="john@example.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Mobile/Phone Number *</label>
                <div class="flex gap-2">
                    <select name="country_code" id="country_code" required
                        class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('country_code') border-red-500 @enderror">
                        <option value="+1" {{ old('country_code', '+1') == '+1' ? 'selected' : '' }}>+1 (US/CA)</option>
                        <option value="+63" {{ old('country_code') == '+63' ? 'selected' : '' }}>+63 (PH)</option>
                        <option value="+81" {{ old('country_code') == '+81' ? 'selected' : '' }}>+81 (JP)</option>
                        <option value="+46" {{ old('country_code') == '+46' ? 'selected' : '' }}>+46 (SE)</option>
                        <option value="+44" {{ old('country_code') == '+44' ? 'selected' : '' }}>+44 (UK)</option>
                        <option value="+61" {{ old('country_code') == '+61' ? 'selected' : '' }}>+61 (AU)</option>
                        <option value="+86" {{ old('country_code') == '+86' ? 'selected' : '' }}>+86 (CN)</option>
                        <option value="+82" {{ old('country_code') == '+82' ? 'selected' : '' }}>+82 (KR)</option>
                        <option value="+65" {{ old('country_code') == '+65' ? 'selected' : '' }}>+65 (SG)</option>
                        <option value="+60" {{ old('country_code') == '+60' ? 'selected' : '' }}>+60 (MY)</option>
                        <option value="+66" {{ old('country_code') == '+66' ? 'selected' : '' }}>+66 (TH)</option>
                        <option value="+84" {{ old('country_code') == '+84' ? 'selected' : '' }}>+84 (VN)</option>
                        <option value="+7" {{ old('country_code') == '+7' ? 'selected' : '' }}>+7 (RU)</option>
                    </select>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                        placeholder="1234567890">
                </div>
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @error('country_code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password *</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Delivery Address</h3>
            <p class="text-sm text-gray-600 mb-4">This information will be used for order delivery. Please provide accurate details.</p>
            
            <div class="mb-4">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Complete Address *</label>
                <textarea name="address" id="address" rows="3" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror"
                    placeholder="Street address, building number, unit/apartment">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Exact Location / Landmarks *</label>
                <textarea name="location" id="location" rows="2" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('location') border-red-500 @enderror"
                    placeholder="Near landmarks, building name, floor, or specific directions for delivery">{{ old('location') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Help delivery personnel find your exact location</p>
                @error('location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="city" class="block text-gray-700 text-sm font-bold mb-2">City *</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror"
                        placeholder="New York">
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="state" class="block text-gray-700 text-sm font-bold mb-2">State/Province</label>
                    <input type="text" name="state" id="state" value="{{ old('state') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('state') border-red-500 @enderror"
                        placeholder="NY">
                    @error('state')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="postal_code" class="block text-gray-700 text-sm font-bold mb-2">Postal/Zip Code *</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('postal_code') border-red-500 @enderror"
                        placeholder="10001">
                    @error('postal_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="country" class="block text-gray-700 text-sm font-bold mb-2">Country *</label>
                    <input type="text" name="country" id="country" value="{{ old('country', 'USA') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('country') border-red-500 @enderror"
                        placeholder="USA">
                    @error('country')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <p class="text-sm text-gray-600">
                <span class="font-semibold">Role:</span> You are registering as a <span class="font-bold text-blue-600">Customer/Buyer</span>. 
                You'll be able to browse products, add to cart, and place orders.
            </p>
        </div>
        
        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold text-lg">
            Create Account
        </button>
    </form>
    
    <p class="mt-4 text-center text-sm text-gray-600">
        Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">Login here</a>
    </p>
</div>
@endsection
