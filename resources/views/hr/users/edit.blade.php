@extends('layouts.app')

@section('title', 'Edit Staff Account - Winbreaker HR')

@section('content')
<h1 class="text-3xl font-bold mb-6">Edit Staff Account</h1>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="{{ route('hr.users.update', $user->id) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">New Password (Optional)</label>
            <input type="password" name="password" id="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Leave empty to keep current password</p>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
            <select name="role" id="role" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                @foreach($roles as $roleValue => $roleLabel)
                    <option value="{{ $roleValue }}" {{ old('role', $user->role) == $roleValue ? 'selected' : '' }}>
                        {{ $roleLabel }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-600">Active account</span>
            </label>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                Update Account
            </button>
            <a href="{{ route('hr.users.index') }}" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection


