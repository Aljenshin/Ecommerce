@extends('layouts.app')

@section('title', 'Shopping Cart - Uni-H-Pen')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold">Cart Items ({{ $cartItems->count() }})</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                        <div class="p-6 hover:bg-gray-50 transition-colors" data-cart-item>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="block">
                                        <img src="{{ $item->product->image_url }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="h-32 w-32 sm:h-24 sm:w-24 object-cover rounded-lg border border-gray-200 hover:border-blue-500 transition-colors">
                                    </a>
                                </div>
                                
                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-lg font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                                                {{ $item->product->name }}
                                            </a>
                                            
                                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                                @if($item->product->brand)
                                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $item->product->brand->name }}</span>
                                                @endif
                                                <span class="text-sm text-gray-500">{{ $item->product->category->name }}</span>
                                            </div>
                                            
                                            @if($item->size || $item->color)
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @if($item->size)
                                                        <span class="text-sm text-gray-600">
                                                            <span class="font-medium">Size:</span> {{ $item->size }}
                                                        </span>
                                                    @endif
                                                    @if($item->color)
                                                        <span class="text-sm text-gray-600">
                                                            <span class="font-medium">Color:</span> {{ $item->color }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            <div class="mt-3 flex items-center gap-4">
                                                <!-- Quantity Control -->
                                                <form method="POST" action="{{ route('cart.update', $item->id) }}" class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <label for="quantity-{{ $item->id }}" class="text-sm font-medium text-gray-700">Qty:</label>
                                                    <div class="flex items-center border border-gray-300 rounded-md">
                                                        <button type="button" 
                                                                onclick="updateQuantity({{ $item->id }}, {{ max(1, $item->quantity - 1) }}, {{ $item->product->stock }})"
                                                                class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition-colors"
                                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                            </svg>
                                                        </button>
                                                        <input type="number" 
                                                               id="quantity-{{ $item->id }}"
                                                               name="quantity" 
                                                               value="{{ $item->quantity }}" 
                                                               min="1" 
                                                               max="{{ $item->product->stock }}"
                                                               onchange="this.form.submit()"
                                                               class="w-16 px-2 py-1 text-center border-0 focus:outline-none focus:ring-0 text-sm font-medium">
                                                        <button type="button" 
                                                                onclick="updateQuantity({{ $item->id }}, {{ min($item->product->stock, $item->quantity + 1) }}, {{ $item->product->stock }})"
                                                                class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition-colors"
                                                                {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </form>
                                                
                                                <!-- Price -->
                                                <div class="text-lg font-semibold text-gray-900">
                                                    ${{ number_format($item->product->price, 2) }}
                                                </div>
                                            </div>
                                            
                                            <!-- Subtotal -->
                                            <div class="mt-2 text-sm text-gray-600">
                                                Subtotal: <span class="font-semibold text-gray-900">${{ number_format($item->quantity * $item->product->price, 2) }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Remove Button -->
                                        <div class="flex-shrink-0">
                                            <form method="POST" action="{{ route('cart.remove', $item->id) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to remove this item from your cart?')"
                                                        class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition-colors"
                                                        title="Remove item">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Continue Shopping -->
                <div class="mt-4">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Continue Shopping
                    </a>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span class="text-green-600">Free</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span class="text-blue-600">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('checkout') }}" class="block w-full bg-blue-600 text-white text-center py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors mb-3">
                        Proceed to Checkout
                    </a>
                    
                    <div class="text-xs text-gray-500 text-center">
                        <p>🔒 Secure checkout</p>
                        <p class="mt-1">30-day return policy</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Your cart is empty</h2>
            <p class="text-gray-600 mb-6">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Start Shopping
            </a>
        </div>
    @endif
</div>

<script>
    function updateQuantity(itemId, newQuantity, maxStock) {
        if (newQuantity < 1 || newQuantity > maxStock) {
            return;
        }
        
        const form = document.querySelector(`form[action*="${itemId}"]`);
        const input = document.getElementById(`quantity-${itemId}`);
        
        if (form && input) {
            input.value = newQuantity;
            form.submit();
        }
    }
    
    // Update cart badge after form submission
    document.addEventListener('DOMContentLoaded', function() {
        // Update badge immediately if cart was updated
        @if(session('cart_updated'))
            if (typeof updateCartBadge === 'function') {
                updateCartBadge();
            }
            document.dispatchEvent(new Event('cartUpdated'));
        @endif
        
        const forms = document.querySelectorAll('form[action*="cart"]');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    if (typeof updateCartBadge === 'function') {
                        updateCartBadge();
                    }
                    // Dispatch custom event
                    document.dispatchEvent(new Event('cartUpdated'));
                }, 500);
            });
        });
    });
</script>
@endsection
