@extends('layouts.app')

@section('title', $product->name . ' - Uni-H-Pen')

@section('content')
<!-- Breadcrumb Navigation -->
<nav class="mb-6 text-sm">
    <ol class="flex items-center space-x-2 text-gray-600">
        <li><a href="{{ route('home') }}" class="hover:text-blue-600">Home</a></li>
        <li><span>/</span></li>
        @if($product->category)
            <li><a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="hover:text-blue-600">{{ $product->category->name }}</a></li>
            <li><span>/</span></li>
        @endif
        @if($product->brand)
            <li><span>{{ $product->brand->name }}</span></li>
            <li><span>/</span></li>
        @endif
        <li class="text-gray-900 font-medium">{{ $product->name }}</li>
    </ol>
</nav>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div>
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full rounded-lg shadow-md">
    </div>
    
    <div>
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-4xl font-bold">{{ $product->name }}</h1>
            <div class="flex items-center space-x-2">
                <!-- Favorite Button -->
                @auth
                    @php
                        $isFavorite = \App\Models\Favorite::where('user_id', auth()->id())
                            ->where('product_id', $product->id)
                            ->exists();
                    @endphp
                    <form action="{{ route('favorites.toggle', $product->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 rounded-full hover:bg-red-50 transition-colors" title="{{ $isFavorite ? 'Remove from favorites' : 'Add to favorites' }}">
                            <svg class="w-6 h-6 {{ $isFavorite ? 'text-red-500 fill-current' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </form>
                @endauth
                
                <!-- Share Button -->
                <button onclick="shareProduct()" class="p-2 rounded-full hover:bg-blue-50 transition-colors" title="Share product">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                </button>
            </div>
        </div>
        
        @if($product->brand)
            <p class="text-gray-600 mb-2">Brand: <span class="font-semibold text-blue-600">{{ $product->brand->name }}</span></p>
        @endif
        <p class="text-gray-600 mb-4">Category: <span class="font-semibold">{{ $product->category->name }}</span></p>
        
        <!-- Product Ratings -->
        <div class="mb-4">
            @php
                $avgRating = $product->average_rating;
                $totalRatings = $product->total_ratings;
            @endphp
            <div class="flex items-center space-x-2 mb-2">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($avgRating) ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <span class="text-gray-700 font-semibold">{{ number_format($avgRating, 1) }}</span>
                <span class="text-gray-500 text-sm">({{ $totalRatings }} {{ $totalRatings == 1 ? 'review' : 'reviews' }})</span>
            </div>
        </div>
        
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
                <div class="mb-6">
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
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Add to Cart Form -->
                        <form method="POST" action="{{ route('cart.add', $product->id) }}" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="quantity" id="cart_quantity" value="1">
                            <input type="hidden" name="size" id="cart_size" value="">
                            <input type="hidden" name="color" id="cart_color" value="">
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Add to Cart
                            </button>
                        </form>
                        
                        <!-- Buy Now Form -->
                        <form method="POST" action="{{ route('checkout.buynow') }}" id="buyNowForm">
                            @csrf
                            <input type="hidden" name="product" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="buynow_quantity" value="1">
                            <input type="hidden" name="size" id="buynow_size" value="">
                            <input type="hidden" name="color" id="buynow_color" value="">
                            <button type="submit" class="w-full bg-orange-500 text-white py-3 px-6 rounded-lg font-semibold hover:bg-orange-600 transition-colors">
                                Buy Now
                            </button>
                        </form>
                    </div>
                </div>
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

<!-- Shopping Guarantee Section -->
<div class="mt-8 mb-8 bg-gray-50 rounded-lg p-6">
    <h3 class="text-xl font-bold mb-4">Shopping Guarantee</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex items-start space-x-3">
            <svg class="w-6 h-6 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h4 class="font-semibold">Authentic Products</h4>
                <p class="text-sm text-gray-600">100% genuine items guaranteed</p>
            </div>
        </div>
        <div class="flex items-start space-x-3">
            <svg class="w-6 h-6 text-blue-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <div>
                <h4 class="font-semibold">Secure Payment</h4>
                <p class="text-sm text-gray-600">Safe and encrypted transactions</p>
            </div>
        </div>
        <div class="flex items-start space-x-3">
            <svg class="w-6 h-6 text-orange-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <div>
                <h4 class="font-semibold">Easy Returns</h4>
                <p class="text-sm text-gray-600">30-day return policy</p>
            </div>
        </div>
    </div>
</div>

<!-- Write Review Section -->
@auth
    @php
        $userReview = $product->ratings()->where('user_id', auth()->id())->first();
    @endphp
    @if(!$userReview)
    <div class="mt-12 border-t pt-8">
        <h2 class="text-3xl font-bold mb-6">Write a Review</h2>
        <form method="POST" action="{{ route('reviews.store', $product->id) }}" class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-2">Your Rating</label>
                <div class="flex items-center space-x-2" id="rating-container">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="rating-star text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input" value="" required>
                @error('rating')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="review" class="block font-semibold mb-2">Your Review (Optional)</label>
                <textarea name="review" id="review" rows="4" maxlength="1000"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('review') border-red-500 @enderror"
                    placeholder="Share your experience with this product..."></textarea>
                <p class="text-xs text-gray-500 mt-1"><span id="char-count">0</span>/1000 characters</p>
                @error('review')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" id="submit-review-btn" class="bg-blue-600 text-white py-2 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                Submit Review
            </button>
        </form>
    </div>
    @else
    <div class="mt-12 border-t pt-8">
        <h2 class="text-3xl font-bold mb-6">Your Review</h2>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= $userReview->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endfor
                <span class="ml-2 text-sm text-gray-500">{{ $userReview->created_at->format('M d, Y') }}</span>
            </div>
            @if($userReview->review)
                <p class="text-gray-700">{{ $userReview->review }}</p>
            @endif
        </div>
    </div>
    @endif
@endauth

<!-- Product Reviews Section -->
@if($product->ratings()->count() > 0)
<div class="mt-12 border-t pt-8">
    <h2 class="text-3xl font-bold mb-6">Customer Reviews ({{ $product->total_ratings }})</h2>
    <div class="space-y-6">
        @foreach($product->ratings()->with('user')->latest()->take(20)->get() as $rating)
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h4 class="font-semibold text-gray-900">{{ $rating->user->name }}</h4>
                    <div class="flex items-center mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                        <span class="ml-2 text-sm text-gray-500">{{ $rating->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            @if($rating->review)
                <p class="text-gray-700 mt-3">{{ $rating->review }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@else
<div class="mt-12 border-t pt-8">
    <h2 class="text-3xl font-bold mb-6">Customer Reviews</h2>
    <p class="text-gray-600">No reviews yet. Be the first to review this product!</p>
</div>
@endif

@if($relatedProducts->count() > 0)
<div class="mt-12">
    <h2 class="text-3xl font-bold mb-6">Related Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($relatedProducts as $relatedProduct)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-2xl hover:scale-105 transform transition-all duration-300 group">
            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="block">
                <div class="relative overflow-hidden">
                    <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2 line-clamp-2 text-gray-900 group-hover:text-blue-600 transition-colors">{{ $relatedProduct->name }}</h3>
                    <p class="text-blue-600 font-bold text-xl">${{ number_format($relatedProduct->price, 2) }}</p>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

<script>
    function shareProduct() {
        const url = window.location.href;
        const title = '{{ $product->name }}';
        
        if (navigator.share) {
            navigator.share({
                title: title,
                text: 'Check out this product on Uni-H-Pen',
                url: url,
            }).catch(console.error);
        } else {
            // Fallback: Copy to clipboard
            navigator.clipboard.writeText(url).then(() => {
                alert('Product link copied to clipboard!');
            });
        }
    }
    
    // Sync form fields for Add to Cart and Buy Now
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const sizeSelect = document.getElementById('size');
        const colorSelect = document.getElementById('color');
        
        // Update both forms when quantity changes
        if (quantityInput) {
            quantityInput.addEventListener('change', function() {
                const cartQty = document.getElementById('cart_quantity');
                const buyNowQty = document.getElementById('buynow_quantity');
                if (cartQty) cartQty.value = this.value;
                if (buyNowQty) buyNowQty.value = this.value;
            });
            
            quantityInput.addEventListener('input', function() {
                const cartQty = document.getElementById('cart_quantity');
                const buyNowQty = document.getElementById('buynow_quantity');
                if (cartQty) cartQty.value = this.value;
                if (buyNowQty) buyNowQty.value = this.value;
            });
            
            // Initialize values
            const cartQty = document.getElementById('cart_quantity');
            const buyNowQty = document.getElementById('buynow_quantity');
            if (cartQty) cartQty.value = quantityInput.value;
            if (buyNowQty) buyNowQty.value = quantityInput.value;
        }
        
        // Update both forms when size changes
        if (sizeSelect) {
            sizeSelect.addEventListener('change', function() {
                const cartSize = document.getElementById('cart_size');
                const buyNowSize = document.getElementById('buynow_size');
                if (cartSize) cartSize.value = this.value;
                if (buyNowSize) buyNowSize.value = this.value;
            });
        }
        
        // Update both forms when color changes
        if (colorSelect) {
            colorSelect.addEventListener('change', function() {
                const cartColor = document.getElementById('cart_color');
                const buyNowColor = document.getElementById('buynow_color');
                if (cartColor) cartColor.value = this.value;
                if (buyNowColor) buyNowColor.value = this.value;
            });
        }
        
        // Rating stars functionality
        const ratingContainer = document.getElementById('rating-container');
        const ratingInput = document.getElementById('rating-input');
        const submitBtn = document.getElementById('submit-review-btn');
        const ratingStars = document.querySelectorAll('.rating-star');
        
        if (ratingContainer && ratingInput && submitBtn) {
            ratingStars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    ratingInput.value = rating;
                    submitBtn.disabled = false;
                    
                    ratingStars.forEach((s, i) => {
                        if (i < rating) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-400');
                        } else {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                        }
                    });
                });
                
                star.addEventListener('mouseenter', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    ratingStars.forEach((s, i) => {
                        if (i < rating) {
                            s.classList.add('text-yellow-400');
                            s.classList.remove('text-gray-300');
                        }
                    });
                });
            });
            
            ratingContainer.addEventListener('mouseleave', function() {
                const currentRating = parseInt(ratingInput.value) || 0;
                ratingStars.forEach((s, i) => {
                    if (i < currentRating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            });
            
            // Prevent form submission without rating
            const reviewForm = document.querySelector('form[action*="reviews"]');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function(e) {
                    if (!ratingInput.value || ratingInput.value === '0' || ratingInput.value === '') {
                        e.preventDefault();
                        alert('Please select a rating before submitting your review.');
                        return false;
                    }
                });
            }
        }
        
        // Character counter for review
        const reviewTextarea = document.getElementById('review');
        const charCount = document.getElementById('char-count');
        if (reviewTextarea && charCount) {
            reviewTextarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        }
    });
</script>
@endsection

