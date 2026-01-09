@extends('layouts.app')

@section('title', 'Checkout - Uni-H-Pen')

@section('content')
<h1 class="text-3xl font-bold mb-6">Checkout</h1>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Order Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Shipping Information</h2>
        
        <form method="POST" action="{{ route('orders.store') }}">
            @csrf
            
            @if(isset($isBuyNow) && $isBuyNow)
                @foreach($cartItems as $item)
                    <input type="hidden" name="buy_now_items[{{ $loop->index }}][product_id]" value="{{ $item->product_id }}">
                    <input type="hidden" name="buy_now_items[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}">
                    <input type="hidden" name="buy_now_items[{{ $loop->index }}][size]" value="{{ $item->size ?? '' }}">
                    <input type="hidden" name="buy_now_items[{{ $loop->index }}][color]" value="{{ $item->color ?? '' }}">
                @endforeach
            @endif
            
            <div class="mb-4">
                <label for="shipping_address" class="block text-gray-700 text-sm font-bold mb-2">Address</label>
                <textarea name="shipping_address" id="shipping_address" rows="2" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('shipping_address') border-red-500 @enderror">{{ old('shipping_address', $user->address ?? '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Using your saved address. You can edit if needed.</p>
                @error('shipping_address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="shipping_location" class="block text-gray-700 text-sm font-bold mb-2">Exact Location / Landmarks</label>
                <textarea name="shipping_location" id="shipping_location" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('shipping_location') border-red-500 @enderror">{{ old('shipping_location', $user->location ?? '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Specific directions for delivery personnel</p>
                @error('shipping_location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="shipping_city" class="block text-gray-700 text-sm font-bold mb-2">City</label>
                    <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city', $user->city ?? '') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('shipping_city') border-red-500 @enderror">
                    @error('shipping_city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="shipping_state" class="block text-gray-700 text-sm font-bold mb-2">State</label>
                    <input type="text" name="shipping_state" id="shipping_state" value="{{ old('shipping_state', $user->state ?? '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('shipping_state') border-red-500 @enderror">
                    @error('shipping_state')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="shipping_postal_code" class="block text-gray-700 text-sm font-bold mb-2">Postal Code</label>
                    <input type="text" name="shipping_postal_code" id="shipping_postal_code" value="{{ old('shipping_postal_code', $user->postal_code ?? '') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('shipping_postal_code') border-red-500 @enderror">
                    @error('shipping_postal_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="shipping_country" class="block text-gray-700 text-sm font-bold mb-2">Country</label>
                    <input type="text" name="shipping_country" id="shipping_country" value="{{ old('shipping_country', $user->country ?? 'USA') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('shipping_country') border-red-500 @enderror">
                    @error('shipping_country')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mb-6">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700">
                Place Order
            </button>
        </form>
    </div>
    
    <!-- Order Summary -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Order Summary</h2>
        
        <div class="space-y-4 mb-6">
            @foreach($cartItems as $item)
            <div class="flex justify-between items-center border-b pb-4">
                <div class="flex items-center">
                    @php
                        if (is_object($item->product)) {
                            $product = $item->product;
                        } else {
                            $product = \App\Models\Product::with(['category', 'brand'])->find($item->product_id ?? $item->product->id ?? null);
                        }
                    @endphp
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-16 w-16 object-cover rounded">
                    <div class="ml-4">
                        <p class="font-semibold">{{ $product->name }}</p>
                        <div class="flex items-center space-x-2 mt-1">
                            @if($product->brand)
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">{{ $product->brand->name }}</span>
                            @endif
                            <span class="text-sm text-gray-600">{{ $product->category->name }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Qty: {{ $item->quantity }}</p>
                        @if(isset($item->size) && $item->size)
                            <p class="text-xs text-gray-500">Size: {{ $item->size }}</p>
                        @endif
                        @if(isset($item->color) && $item->color)
                            <p class="text-xs text-gray-500">Color: {{ $item->color }}</p>
                        @endif
                    </div>
                </div>
                <p class="font-semibold">${{ number_format($item->quantity * $product->price, 2) }}</p>
            </div>
            @endforeach
        </div>
        
        <div class="border-t pt-4">
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-semibold">Total:</span>
                <span class="text-2xl font-bold text-blue-600">${{ number_format($total, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

