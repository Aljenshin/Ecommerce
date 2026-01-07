@extends('layouts.app')

@section('title', 'Shopping Cart - Winbreaker')

@section('content')
<h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

@if($cartItems->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-16 w-16 object-cover rounded">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->product->category->name }}</div>
                                    @if($item->size || $item->color)
                                        <div class="text-xs text-gray-500 mt-1">
                                            @if($item->size)
                                                Size: {{ $item->size }}
                                            @endif
                                            @if($item->color)
                                                <span class="ml-2">Color: {{ $item->color }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($item->product->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form method="POST" action="{{ route('cart.update', $item->id) }}" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" 
                                    class="w-20 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="this.form.submit()">
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ${{ number_format($item->quantity * $item->product->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form method="POST" action="{{ route('cart.remove', $item->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="bg-gray-50 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-lg font-semibold">Total: ${{ number_format($total, 2) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    </div>
@else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <p class="text-gray-600 text-lg mb-4">Your cart is empty.</p>
        <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 inline-block">
            Continue Shopping
        </a>
    </div>
@endif
@endsection

