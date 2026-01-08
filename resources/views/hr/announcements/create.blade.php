@extends('layouts.app')

@section('title', 'Create Announcement - Winbreaker HR')

@section('content')
<div class="mb-6">
    <a href="{{ route('hr.announcements.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Back to Announcements</a>
    <h1 class="text-3xl font-bold">Create Announcement</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="{{ route('hr.announcements.store') }}">
        @csrf

        <!-- Title -->
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                   class="w-full border rounded-lg px-4 py-2 @error('title') border-red-500 @enderror" 
                   placeholder="Enter announcement title">
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Message -->
        <div class="mb-6">
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
            <textarea name="message" id="message" rows="6" required 
                      class="w-full border rounded-lg px-4 py-2 @error('message') border-red-500 @enderror" 
                      placeholder="Enter announcement message">{{ old('message') }}</textarea>
            @error('message')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Type and Audience -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                <select name="type" id="type" required 
                        class="w-full border rounded-lg px-4 py-2 @error('type') border-red-500 @enderror">
                    <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="schedule" {{ old('type') == 'schedule' ? 'selected' : '' }}>Schedule</option>
                    <option value="shift_change" {{ old('type') == 'shift_change' ? 'selected' : '' }}>Shift Change</option>
                    <option value="notice" {{ old('type') == 'notice' ? 'selected' : '' }}>Notice</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="audience" class="block text-sm font-medium text-gray-700 mb-2">Audience *</label>
                <select name="audience" id="audience" required 
                        class="w-full border rounded-lg px-4 py-2 @error('audience') border-red-500 @enderror">
                    <option value="all" {{ old('audience') == 'all' ? 'selected' : '' }}>All Staff</option>
                    <option value="department" {{ old('audience') == 'department' ? 'selected' : '' }}>Specific Department</option>
                </select>
                @error('audience')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Target Department (conditional) -->
        <div class="mb-6" id="department-field" style="display: none;">
            <label for="target_department" class="block text-sm font-medium text-gray-700 mb-2">Target Department</label>
            <input type="text" name="target_department" id="target_department" value="{{ old('target_department') }}" 
                   class="w-full border rounded-lg px-4 py-2 @error('target_department') border-red-500 @enderror" 
                   placeholder="Enter department name">
            @error('target_department')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Urgent and Effective Date -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_urgent" value="1" {{ old('is_urgent') ? 'checked' : '' }} 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Mark as Urgent</span>
                </label>
            </div>
            <div>
                <label for="effective_date" class="block text-sm font-medium text-gray-700 mb-2">Effective Date</label>
                <input type="date" name="effective_date" id="effective_date" value="{{ old('effective_date') }}" 
                       class="w-full border rounded-lg px-4 py-2 @error('effective_date') border-red-500 @enderror">
                @error('effective_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                Create Announcement
            </button>
            <a href="{{ route('hr.announcements.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('audience').addEventListener('change', function() {
        const departmentField = document.getElementById('department-field');
        if (this.value === 'department') {
            departmentField.style.display = 'block';
        } else {
            departmentField.style.display = 'none';
        }
    });
    
    // Trigger on page load if old value exists
    if (document.getElementById('audience').value === 'department') {
        document.getElementById('department-field').style.display = 'block';
    }
</script>
@endsection

