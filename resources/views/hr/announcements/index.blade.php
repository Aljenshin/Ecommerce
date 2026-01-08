@extends('layouts.app')

@section('title', 'Announcements - Winbreaker HR')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Announcements</h1>
    <a href="{{ route('hr.announcements.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700">
        + Create Announcement
    </a>
</div>

<!-- Announcements List -->
<div class="space-y-4">
    @forelse($announcements as $announcement)
    <div class="bg-white rounded-lg shadow-md p-6 {{ $announcement->is_urgent ? 'border-l-4 border-red-500' : '' }}">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-xl font-bold">{{ $announcement->title }}</h2>
                    @if($announcement->is_urgent)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Urgent</span>
                    @endif
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($announcement->type) }}</span>
                </div>
                <p class="text-gray-600 mb-3">{{ $announcement->message }}</p>
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span>By: {{ $announcement->creator->name }}</span>
                    <span>•</span>
                    <span>{{ $announcement->created_at->format('M d, Y h:i A') }}</span>
                    @if($announcement->effective_date)
                        <span>•</span>
                        <span>Effective: {{ $announcement->effective_date->format('M d, Y') }}</span>
                    @endif
                    <span>•</span>
                    <span>Audience: {{ ucfirst($announcement->audience) }}</span>
                    @if($announcement->target_department)
                        <span>•</span>
                        <span>Department: {{ $announcement->target_department }}</span>
                    @endif
                </div>
            </div>
            <form method="POST" action="{{ route('hr.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?');" class="ml-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">Delete</button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <p class="text-gray-500 text-lg">No announcements yet.</p>
        <a href="{{ route('hr.announcements.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
            Create First Announcement
        </a>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($announcements->hasPages())
<div class="mt-6">
    {{ $announcements->links() }}
</div>
@endif
@endsection

