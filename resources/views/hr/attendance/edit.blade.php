@extends('layouts.app')

@section('title', 'Edit Attendance - Winbreaker HR')

@section('content')
<div class="mb-6">
    <a href="{{ route('hr.attendance.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Back to Attendance</a>
    <h1 class="text-3xl font-bold">Edit Attendance: {{ $attendance->user->name }}</h1>
    <p class="text-gray-600">{{ $attendance->date->format('F d, Y') }}</p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="{{ route('hr.attendance.update', $attendance) }}">
        @csrf
        @method('PUT')

        <!-- Time In -->
        <div class="mb-6">
            <label for="time_in" class="block text-sm font-medium text-gray-700 mb-2">Time In</label>
            <input type="time" name="time_in" id="time_in" 
                   value="{{ old('time_in', $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '') }}" 
                   class="w-full border rounded-lg px-4 py-2 @error('time_in') border-red-500 @enderror">
            @error('time_in')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Time Out -->
        <div class="mb-6">
            <label for="time_out" class="block text-sm font-medium text-gray-700 mb-2">Time Out</label>
            <input type="time" name="time_out" id="time_out" 
                   value="{{ old('time_out', $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '') }}" 
                   class="w-full border rounded-lg px-4 py-2 @error('time_out') border-red-500 @enderror">
            @error('time_out')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
            <select name="status" id="status" required 
                    class="w-full border rounded-lg px-4 py-2 @error('status') border-red-500 @enderror">
                <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Present</option>
                <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Late</option>
                <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                <option value="undertime" {{ old('status', $attendance->status) == 'undertime' ? 'selected' : '' }}>Undertime</option>
                <option value="overtime" {{ old('status', $attendance->status) == 'overtime' ? 'selected' : '' }}>Overtime</option>
            </select>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notes -->
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" id="notes" rows="3" 
                      class="w-full border rounded-lg px-4 py-2 @error('notes') border-red-500 @enderror">{{ old('notes', $attendance->notes) }}</textarea>
            @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Current Info Display -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold mb-2">Current Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Minutes Late:</span>
                    <span class="font-semibold">{{ $attendance->minutes_late ?? 0 }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Minutes Undertime:</span>
                    <span class="font-semibold">{{ $attendance->minutes_undertime ?? 0 }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Minutes Overtime:</span>
                    <span class="font-semibold">{{ $attendance->minutes_overtime ?? 0 }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Total Hours:</span>
                    <span class="font-semibold">{{ $attendance->total_hours ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                Update Attendance
            </button>
            <a href="{{ route('hr.attendance.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

