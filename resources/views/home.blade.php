@extends('layouts.app')

@section('title', 'Winbreaker - Home')

@section('content')
<div class="mb-8">
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-8 md:p-12">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to Winbreaker</h1>
        <p class="text-xl mb-6">Discover premium quality apparel and accessories</p>
        <a href="{{ route('products.index') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
            Shop Now
        </a>
    </div>
</div>

<!-- Categories -->
@if($categories->count() > 0)
<div class="mb-12">
    <h2 class="text-3xl font-bold mb-6">Shop by Category</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($categories as $category)
        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
            <div class="text-4xl mb-2">👕</div>
            <h3 class="font-semibold text-gray-800">{{ $category->name }}</h3>
            <p class="text-sm text-gray-500">{{ $category->products_count }} items</p>
        </a>
        @endforeach
    </div>
</div>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<div>
    <h2 class="text-3xl font-bold mb-6">Featured Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <a href="{{ route('products.show', $product->slug) }}">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-2">{{ $product->category->name }}</p>
                    <p class="text-blue-600 font-bold text-xl">${{ number_format($product->price, 2) }}</p>
                    @if($product->stock > 0)
                        <span class="text-green-600 text-sm">In Stock</span>
                    @else
                        <span class="text-red-600 text-sm">Out of Stock</span>
                    @endif
                </div>
            </a>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-8">
        <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
            View All Products
        </a>
    </div>
</div>
@endif
@endsection

