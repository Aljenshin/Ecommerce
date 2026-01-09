@extends('layouts.app')

@section('title', 'Products - Uni-H-Pen')

@section('content')
<!-- Breadcrumb Navigation -->
<nav class="mb-6 text-sm">
    <ol class="flex items-center space-x-2 text-gray-600">
        <li><a href="{{ route('home') }}" class="hover:text-blue-600">Home</a></li>
        <li><span>/</span></li>
        <li class="text-gray-900 font-medium">Products</li>
        @if(request('category'))
            @php $category = \App\Models\Category::find(request('category')); @endphp
            @if($category)
                <li><span>/</span></li>
                <li class="text-gray-900 font-medium">{{ $category->name }}</li>
            @endif
        @endif
        @if(request('brand'))
            @php $brand = \App\Models\Brand::find(request('brand')); @endphp
            @if($brand)
                <li><span>/</span></li>
                <li class="text-gray-900 font-medium">{{ $brand->name }}</li>
            @endif
        @endif
    </ol>
</nav>

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
            
            <select name="brand" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Brands</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                Filter
            </button>
        </form>
    </div>
    
    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-2xl hover:scale-105 transform transition-all duration-300 group">
                <a href="{{ route('products.show', $product->slug) }}" class="block">
                    <div class="relative overflow-hidden">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2 line-clamp-2 text-gray-900 group-hover:text-blue-600 transition-colors">{{ $product->name }}</h3>
                        <div class="flex items-center space-x-2 mb-2">
                            @if($product->brand)
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $product->brand->name }}</span>
                            @endif
                            <span class="text-gray-600 text-sm">{{ $product->category->name }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-blue-600 font-bold text-xl">${{ number_format($product->price, 2) }}</p>
                        </div>
                        @if($product->stock > 0)
                            <span class="text-green-600 text-sm font-medium bg-green-50 px-2 py-1 rounded inline-block">In Stock ({{ $product->stock }})</span>
                        @else
                            <span class="text-red-600 text-sm font-medium bg-red-50 px-2 py-1 rounded inline-block">Out of Stock</span>
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

