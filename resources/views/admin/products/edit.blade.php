@extends('layouts.app')

@section('title', 'Edit Product - Uni-H-Pen')

@section('content')
<h1 class="text-3xl font-bold mb-6">Edit Product</h1>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Product Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                <select name="category_id" id="category_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="brand_id" class="block text-gray-700 text-sm font-bold mb-2">Brand</label>
                <select name="brand_id" id="brand_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('brand_id') border-red-500 @enderror">
                    <option value="">Select a brand (Optional)</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
                @error('brand_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" id="description" rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror">
                @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stock') border-red-500 @enderror">
                @error('stock')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mb-4">
            <label for="images" class="block text-gray-700 text-sm font-bold mb-2">Product Images</label>
            <p class="text-sm text-gray-600 mb-3">Upload 1 to 5 images to show different colors or variations</p>
            
            <!-- Existing Images -->
            @if($product->images->count() > 0)
                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Current Images ({{ $product->images->count() }}/5):</p>
                    <div id="existing-images-grid" class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @foreach($product->images as $image)
                            <div class="relative" id="existing-image-{{ $image->id }}">
                                <img src="{{ $image->image_url }}" alt="Product image" class="h-32 w-full object-cover object-top rounded-lg border-2 border-gray-300">
                                <button type="button"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors"
                                        title="Delete image"
                                        onclick="markImageForDeletion({{ $image->id }})">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                                <p class="text-xs text-gray-600 mt-1 text-center">Image {{ $loop->iteration }}</p>
                            </div>
                        @endforeach
                    </div>
                    <!-- Hidden inputs will be injected here so the server deletes images on submit -->
                    <div id="delete-images-inputs"></div>

                    <!-- Pending deletions list -->
                    <div id="pending-deletions" class="mt-3 hidden">
                        <p class="text-xs text-gray-700 font-semibold mb-2">Pending deletion (will be deleted after you click “Update Product”):</p>
                        <div id="pending-deletions-list" class="flex flex-wrap gap-2"></div>
                    </div>

                    <p class="text-xs text-gray-500 mt-2">Click the X to remove an image (you can Undo before saving)</p>
                </div>
            @endif
            
            <!-- New Images Preview -->
            <div id="images-preview-container" class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4"></div>
            
            @php
                $remainingSlots = 5 - $product->images->count();
            @endphp
            
            @if($remainingSlots > 0)
                <input type="file" name="images[]" id="images" accept="image/*" multiple onchange="previewMultipleImages(this)"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('images') border-red-500 @enderror">
                <p class="text-sm text-gray-500 mt-1">You can add up to {{ $remainingSlots }} more image(s). Supported formats: JPG, PNG, GIF (Max: 2MB each)</p>
            @else
                <p class="text-sm text-yellow-600 mt-1">Maximum of 5 images reached. Delete an image to add a new one.</p>
            @endif
            
            <p class="text-sm text-red-600 mt-1" id="image-count-warning"></p>
            @error('images')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-600">Active (visible to customers)</span>
            </label>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                Update Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    let selectedImages = [];
    const maxImages = {{ 5 - $product->images->count() }};
    const currentImagesCount = {{ $product->images->count() }};

    function previewMultipleImages(input) {
        const previewContainer = document.getElementById('images-preview-container');
        const warningElement = document.getElementById('image-count-warning');
        previewContainer.innerHTML = '';
        warningElement.textContent = '';
        
        if (input.files && input.files.length > 0) {
            // Limit to remaining slots
            const files = Array.from(input.files).slice(0, maxImages);
            
            if (input.files.length > maxImages) {
                warningElement.textContent = `Only the first ${maxImages} image(s) will be uploaded.`;
            }
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'relative';
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" class="h-32 w-full object-cover rounded-lg border-2 border-green-400">
                        <button type="button" onclick="removeImagePreview(${index})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <p class="text-xs text-green-600 mt-1 text-center font-semibold">New Image ${index + 1}</p>
                    `;
                    previewContainer.appendChild(previewDiv);
                };
                
                reader.readAsDataURL(file);
            });
            
            selectedImages = files;
        }
    }
    
    function removeImagePreview(index) {
        const input = document.getElementById('images');
        const dt = new DataTransfer();
        
        Array.from(input.files).forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        input.files = dt.files;
        previewMultipleImages(input);
    }
    
    // Existing image deletion UX:
    // Clicking X removes the thumbnail immediately, but we keep a hidden input so the server deletes it on submit.
    const pendingDeletions = new Map(); // imageId -> { node, inputNode, pillNode }

    function markImageForDeletion(imageId) {
        if (pendingDeletions.has(imageId)) return;

        const imageNode = document.getElementById(`existing-image-${imageId}`);
        const inputsContainer = document.getElementById('delete-images-inputs');
        const pendingWrap = document.getElementById('pending-deletions');
        const pendingList = document.getElementById('pending-deletions-list');

        if (!imageNode || !inputsContainer || !pendingWrap || !pendingList) return;

        // Create hidden input for submit
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_images[]';
        input.value = String(imageId);
        input.id = `delete-image-input-${imageId}`;
        inputsContainer.appendChild(input);

        // Create undo pill
        const pill = document.createElement('div');
        pill.className = 'flex items-center gap-2 bg-red-50 text-red-700 border border-red-200 rounded-full px-3 py-1 text-xs';
        pill.id = `delete-image-pill-${imageId}`;
        pill.innerHTML = `
            <span>Image removed</span>
            <button type="button" class="underline hover:no-underline font-semibold" onclick="undoImageDeletion(${imageId})">Undo</button>
        `;
        pendingList.appendChild(pill);
        pendingWrap.classList.remove('hidden');

        // Remove thumbnail from grid immediately (store node for undo)
        const placeholder = document.createComment(`deleted-image-${imageId}`);
        imageNode.parentNode.insertBefore(placeholder, imageNode);
        imageNode.remove();

        pendingDeletions.set(imageId, { placeholder, node: imageNode, inputNode: input, pillNode: pill });
    }

    function undoImageDeletion(imageId) {
        const entry = pendingDeletions.get(imageId);
        if (!entry) return;

        // Remove hidden input
        entry.inputNode?.remove();

        // Remove pill
        entry.pillNode?.remove();

        // Restore thumbnail in original position
        const grid = document.getElementById('existing-images-grid');
        if (grid && entry.placeholder && entry.node) {
            entry.placeholder.parentNode.insertBefore(entry.node, entry.placeholder);
            entry.placeholder.remove();
        }

        pendingDeletions.delete(imageId);

        // Hide pending wrap if empty
        const pendingList = document.getElementById('pending-deletions-list');
        const pendingWrap = document.getElementById('pending-deletions');
        if (pendingWrap && pendingList && pendingList.children.length === 0) {
            pendingWrap.classList.add('hidden');
        }
    }
</script>
@endsection

