@extends('layouts.app')

@section('title', 'Notifications - Uni-H-Pen')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Notifications</h1>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}" class="inline">
                @csrf
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition-colors">
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div class="space-y-4">
            @foreach($notifications as $notification)
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ $notification->is_read ? 'border-gray-300' : 'border-blue-500' }} hover:shadow-lg transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="font-semibold text-lg {{ $notification->is_read ? 'text-gray-700' : 'text-gray-900' }}">{{ $notification->title }}</h3>
                            @if(!$notification->is_read)
                                <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">New</span>
                            @endif
                        </div>
                        <p class="text-gray-600 mb-2">{{ $notification->message }}</p>
                        <p class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="ml-4">
                        @if($notification->link)
                            <a href="{{ route('notifications.read', $notification->id) }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">
                                View →
                            </a>
                        @else
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-blue-600 text-sm">
                                    Mark as read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow-md">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <p class="text-gray-600 text-lg">No notifications yet</p>
            <p class="text-gray-400 text-sm mt-2">You'll see order updates and important announcements here</p>
        </div>
    @endif
</div>
@endsection

