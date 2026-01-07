@extends('layouts.app')

@section('title', 'Add Category - Winbreaker')

@section('content')
<h1 class="text-3xl font-bold mb-6">Add New Category</h1>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Category Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" id="description" rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                Create Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

