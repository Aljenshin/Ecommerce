@extends('layouts.app')

@section('title', 'Products - Winbreaker')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold mb-6">All Products</h1>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-col md:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." 
                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <select name="category" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                Filter
            </button>
        </form>
    </div>
    
    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <a href="{{ route('products.show', $product->slug) }}">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2">{{ $product->category->name }}</p>
                        <p class="text-blue-600 font-bold text-xl mb-2">${{ number_format($product->price, 2) }}</p>
                        @if($product->stock > 0)
                            <span class="text-green-600 text-sm">In Stock ({{ $product->stock }})</span>
                        @else
                            <span class="text-red-600 text-sm">Out of Stock</span>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-600 text-lg">No products found.</p>
        </div>
    @endif
</div>
@endsection

