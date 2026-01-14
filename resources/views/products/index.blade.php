@extends('layouts.app')

@section('title', 'Products - Uni-H-Pen')

@section('content')
<style>
    .product-card {
        position: relative;
        background: white;
        transition: all 0.3s ease;
    }
    
    .product-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .product-image-container {
        position: relative;
        overflow: hidden;
        background: #f5f5f5;
        aspect-ratio: 3/4;
    }
    
    .product-image-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: top center;
        transition: opacity 0.3s ease;
    }
    
    .product-image-secondary {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: top center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .product-card:hover .product-image-primary {
        opacity: 0;
    }
    
    .product-card:hover .product-image-secondary {
        opacity: 1;
    }
    
    .product-actions {
        position: absolute;
        top: 12px;
        right: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .product-card:hover .product-actions {
        opacity: 1;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        background: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .action-btn.favorite.active {
        background: #ff6b6b;
        color: white;
    }
    
    .product-info {
        padding: 16px 12px;
    }
    
    .product-name {
        font-size: 14px;
        font-weight: 400;
        color: #1a1a1a;
        margin-bottom: 8px;
        line-height: 1.4;
        min-height: 40px;
    }
    
    .product-price {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .product-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #000;
        color: white;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-sidebar {
        background: white;
        padding: 24px;
        border-radius: 4px;
        margin-bottom: 24px;
    }
    
    .filter-section {
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e5e5e5;
    }
    
    .filter-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .filter-title {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-option {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        cursor: pointer;
    }
    
    .filter-option input[type="checkbox"] {
        margin-right: 8px;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
    
    .filter-option label {
        font-size: 14px;
        color: #666;
        cursor: pointer;
    }
    
    .sort-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding: 16px;
        background: white;
        border-radius: 4px;
    }
    
    .results-count {
        font-size: 14px;
        color: #666;
    }
    
    .sort-select {
        padding: 8px 12px;
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        font-size: 14px;
        background: white;
        cursor: pointer;
    }
    
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
        }
        
        .product-info {
            padding: 12px 8px;
        }
        
        .product-name {
            font-size: 13px;
            min-height: 36px;
        }
        
        .product-price {
            font-size: 15px;
        }
        
        .filter-sidebar {
            margin-bottom: 16px;
        }
    }
</style>

<!-- Breadcrumb Navigation -->
<nav class="mb-4 text-sm">
    <ol class="flex items-center space-x-2 text-gray-600">
        <li><a href="{{ route('home') }}" class="hover:text-black transition-colors">Home</a></li>
        <li><span>/</span></li>
        <li class="text-gray-900 font-medium">Products</li>
        @if(request('category'))
            @php $category = \App\Models\Category::find(request('category')); @endphp
            @if($category)
                <li><span>/</span></li>
                <li class="text-gray-900 font-medium">{{ $category->name }}</li>
            @endif
        @endif
    </ol>
</nav>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Filter Sidebar -->
    <aside class="lg:w-64 flex-shrink-0">
        <div class="filter-sidebar sticky top-4">
            <h2 class="text-lg font-semibold mb-6 text-gray-900">Filters</h2>
            
            <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                <!-- Search -->
                <div class="filter-section">
                    <div class="filter-title">Search</div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search products..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-black">
                </div>
                
                <!-- Categories -->
                @if($categories->count() > 0)
                <div class="filter-section">
                    <div class="filter-title">Category</div>
                    <div class="filter-option">
                        <input type="radio" 
                               name="category" 
                               id="category-all" 
                               value=""
                               {{ !request('category') ? 'checked' : '' }}
                               onchange="document.getElementById('filter-form').submit()">
                        <label for="category-all">All Categories</label>
                    </div>
                    @foreach($categories as $category)
                    <div class="filter-option">
                        <input type="radio" 
                               name="category" 
                               id="category-{{ $category->id }}" 
                               value="{{ $category->id }}"
                               {{ request('category') == $category->id ? 'checked' : '' }}
                               onchange="document.getElementById('filter-form').submit()">
                        <label for="category-{{ $category->id }}">{{ $category->name }} ({{ $category->products_count ?? 0 }})</label>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <!-- Brands -->
                @if($brands->count() > 0)
                <div class="filter-section">
                    <div class="filter-title">Brand</div>
                    @php
                        $selectedBrands = is_array(request('brand')) ? request('brand') : (request('brand') ? [request('brand')] : []);
                    @endphp
                    @foreach($brands as $brand)
                    <div class="filter-option">
                        <input type="checkbox" 
                               name="brand[]" 
                               id="brand-{{ $brand->id }}" 
                               value="{{ $brand->id }}"
                               {{ in_array($brand->id, $selectedBrands) ? 'checked' : '' }}
                               onchange="document.getElementById('filter-form').submit()">
                        <label for="brand-{{ $brand->id }}">{{ $brand->name }}</label>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <!-- Price Range -->
                <div class="filter-section">
                    <div class="filter-title">Price</div>
                    <div class="space-y-2">
                        <input type="number" 
                               name="min_price" 
                               value="{{ request('min_price') }}" 
                               placeholder="Min" 
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-black">
                        <input type="number" 
                               name="max_price" 
                               value="{{ request('max_price') }}" 
                               placeholder="Max" 
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-black">
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-black text-white py-2 px-4 rounded text-sm font-semibold hover:bg-gray-800 transition-colors mt-4">
                    Apply Filters
                </button>
                
                @if(request()->hasAny(['search', 'category', 'brand', 'min_price', 'max_price']))
                <a href="{{ route('products.index') }}" class="block text-center text-sm text-gray-500 hover:text-black mt-2">
                    Clear all filters
                </a>
                @endif
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="flex-1">
        <!-- Sort Bar -->
        <div class="sort-bar">
            <div class="results-count">
                @if($products->count() > 0)
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                @else
                    No products found
                @endif
            </div>
            <select name="sort" class="sort-select" onchange="window.location.href = updateQueryParam('sort', this.value)">
                <option value="">Sort by</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
            </select>
        </div>
        
        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="product-grid grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($products as $product)
                <div class="product-card">
                    <a href="{{ route('products.show', $product->slug) }}" class="block">
                        <div class="product-image-container">
                            <div class="product-image-wrapper">
                                @php
                                    $product->load('images');
                                    $images = $product->images->take(3);
                                    $primaryImage = $images->first();
                                    $secondaryImage = $images->count() > 1 ? $images->get(1) : null;
                                @endphp
                                
                                @if($primaryImage)
                                    <img src="{{ $primaryImage->image_url }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image product-image-primary">
                                    @if($secondaryImage)
                                        <img src="{{ $secondaryImage->image_url }}" 
                                             alt="{{ $product->name }}" 
                                             class="product-image product-image-secondary">
                                    @endif
                                @else
                                    <img src="{{ $product->image_url }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image product-image-primary">
                                @endif
                            </div>
                            
                            <!-- Product Badge -->
                            @if($product->stock <= 10 && $product->stock > 0)
                                <div class="product-badge">Low Stock</div>
                            @elseif($product->stock == 0)
                                <div class="product-badge" style="background: #d32f2f;">Out of Stock</div>
                            @endif
                            
                            <!-- Quick Actions -->
                            <div class="product-actions">
                                @auth
                                    @php
                                        $isFavorite = \App\Models\Favorite::where('user_id', auth()->id())
                                            ->where('product_id', $product->id)
                                            ->exists();
                                    @endphp
                                    <form action="{{ route('favorites.toggle', $product->id) }}" method="POST" class="inline" onclick="event.preventDefault(); this.submit();">
                                        @csrf
                                        <button type="submit" class="action-btn favorite {{ $isFavorite ? 'active' : '' }}" title="Add to favorites">
                                            <svg class="w-5 h-5" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </div>
                        
                        <div class="product-info">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <div class="product-price">${{ number_format($product->price, 2) }}</div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-600 text-lg mb-2">No products found</p>
                <p class="text-gray-500 text-sm mb-6">Try adjusting your filters</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-black text-white px-6 py-2 rounded text-sm font-semibold hover:bg-gray-800 transition-colors">
                    Clear Filters
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    function updateQueryParam(key, value) {
        const url = new URL(window.location.href);
        if (value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
        return url.toString();
    }
</script>
@endsection
