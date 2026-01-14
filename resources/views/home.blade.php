@extends('layouts.app')

@section('title', 'Uni-H-Pen - Home')

@section('content')
<div class="mb-8">
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-8 md:p-12">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to Uni-H-Pen</h1>
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
        @php
        $categoryIcons = [
            'T-Shirts' => '👕',
            'Polo Shirts' => '👔',
            'Hoodies' => '🧥',
            'Jackets' => '🧥',
            'Pants' => '👖',
            'Shorts' => '🩳',
            'Caps / Hats' => '🧢',
            'Accessories' => '🎒'
        ];
        @endphp
        @foreach($categories as $category)
        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl hover:scale-105 transform transition-all duration-300 hover:bg-blue-50">
            <div class="text-4xl mb-2">{{ $categoryIcons[$category->name] ?? '👕' }}</div>
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
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-2xl hover:scale-105 transform transition-all duration-300 group">
            <a href="{{ route('products.show', $product->slug) }}" class="block">
                <div class="relative overflow-hidden bg-gray-100 flex items-center justify-center" style="min-height: 256px;">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-auto max-h-80 object-contain object-top group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300 pointer-events-none"></div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2 line-clamp-2 text-gray-900 group-hover:text-blue-600 transition-colors">{{ $product->name }}</h3>
                    <div class="flex items-center space-x-2 mb-2">
                        @if($product->brand)
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $product->brand->name }}</span>
                        @endif
                        <span class="text-gray-600 text-sm">{{ $product->category->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-blue-600 font-bold text-xl">${{ number_format($product->price, 2) }}</p>
                        @if($product->stock > 0)
                            <span class="text-green-600 text-sm font-medium bg-green-50 px-2 py-1 rounded">In Stock</span>
                        @else
                            <span class="text-red-600 text-sm font-medium bg-red-50 px-2 py-1 rounded">Out of Stock</span>
                        @endif
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-8">
        <a href="{{ route('products.index') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 hover:shadow-lg transform hover:scale-105 transition-all duration-300">
            View All Products
        </a>
    </div>
</div>
@endif
@endsection

