@extends('layouts.app')

@section('title', $product->name . ' - Winbreaker')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div>
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full rounded-lg shadow-md">
    </div>
    
    <div>
        <h1 class="text-4xl font-bold mb-4">{{ $product->name }}</h1>
        <p class="text-gray-600 mb-4">Category: <span class="font-semibold">{{ $product->category->name }}</span></p>
        <p class="text-blue-600 font-bold text-3xl mb-4">${{ number_format($product->price, 2) }}</p>
        
        @if($product->description)
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Description</h2>
                <p class="text-gray-700">{{ $product->description }}</p>
            </div>
        @endif
        
        <div class="mb-6">
            @if($product->stock > 0)
                <span class="text-green-600 font-semibold">In Stock ({{ $product->stock }} available)</span>
            @else
                <span class="text-red-600 font-semibold">Out of Stock</span>
            @endif
        </div>

        @auth
            @if($product->stock > 0)
                <form method="POST" action="{{ route('cart.add', $product->id) }}" class="mb-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="size" class="block font-semibold mb-1">Size</label>
                            <select name="size" id="size" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select size</option>
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                        </div>

                        <div>
                            <label for="color" class="block font-semibold mb-1">Color</label>
                            <select name="color" id="color" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select color</option>
                                <option value="Black">Black</option>
                                <option value="White">White</option>
                                <option value="Gray">Gray</option>
                                <option value="Blue">Blue</option>
                                <option value="Red">Red</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <label for="quantity" class="font-semibold">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                            class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700">
                        Add to Cart
                    </button>
                </form>
            @else
                <button disabled class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg font-semibold cursor-not-allowed">
                    Out of Stock
                </button>
            @endif
        @else
            <a href="{{ route('login') }}" class="block w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 text-center">
                Login to Add to Cart
            </a>
        @endauth
    </div>
</div>

@if($relatedProducts->count() > 0)
<div class="mt-12">
    <h2 class="text-3xl font-bold mb-6">Related Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($relatedProducts as $relatedProduct)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <a href="{{ route('products.show', $relatedProduct->slug) }}">
                <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2">{{ $relatedProduct->name }}</h3>
                    <p class="text-blue-600 font-bold text-xl">${{ number_format($relatedProduct->price, 2) }}</p>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

