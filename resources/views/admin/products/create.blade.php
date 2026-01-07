@extends('layouts.app')

@section('title', 'Add Product - Winbreaker')

@section('content')
<h1 class="text-3xl font-bold mb-6">Add New Product</h1>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Product Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
            <select name="category_id" id="category_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                <option value="">Select a category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" id="description" rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror">
                @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stock') border-red-500 @enderror">
                @error('stock')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mb-4">
            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Product Image</label>
            <input type="file" name="image" id="image" accept="image/*"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror">
            @error('image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-600">Active (visible to customers)</span>
            </label>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                Create Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

