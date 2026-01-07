@extends('layouts.app')

@section('title', 'HR Dashboard - Winbreaker')

@section('content')
<h1 class="text-3xl font-bold mb-6">HR Dashboard</h1>
<p class="text-gray-600 mb-8">Employee management and operations</p>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Staff</h3>
        <p class="text-3xl font-bold text-blue-600">{{ $staffCount }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $activeStaff }} active</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-gray-600 text-sm font-semibold mb-2">Pending Leaves</h3>
        <p class="text-3xl font-bold text-yellow-600">{{ $pendingLeaves }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-gray-600 text-sm font-semibold mb-2">Today's Attendance</h3>
        <p class="text-3xl font-bold text-green-600">{{ $todayAttendances }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $todayLates }} lates, {{ $todayAbsents }} absents</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-gray-600 text-sm font-semibold mb-2">Recent Announcements</h3>
        <p class="text-3xl font-bold text-purple-600">{{ $recentAnnouncements->count() }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Pending Leave Requests -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Pending Leave Requests</h2>
        @if($recentLeaves->where('status', 'pending')->count() > 0)
            <div class="space-y-3">
                @foreach($recentLeaves->where('status', 'pending') as $leave)
                <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 rounded">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold">{{ $leave->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $leave->leave_type }} - {{ $leave->days_requested }} days</p>
                            <p class="text-xs text-gray-500">{{ $leave->start_date->format('M d') }} to {{ $leave->end_date->format('M d') }}</p>
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('hr.leaves.approve', $leave->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-semibold">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('hr.leaves.reject', $leave->id) }}" class="inline" onsubmit="return confirm('Reject this leave request?');">
                                @csrf
                                <input type="text" name="remarks" placeholder="Reason" required class="text-xs border rounded px-2 py-1">
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">Reject</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No pending leave requests.</p>
        @endif
    </div>

    <!-- Recent Announcements -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Recent Announcements</h2>
        @if($recentAnnouncements->count() > 0)
            <div class="space-y-3">
                @foreach($recentAnnouncements as $announcement)
                <div class="border-b pb-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold">{{ $announcement->title }}</p>
                            <p class="text-sm text-gray-600">{{ Str::limit($announcement->message, 100) }}</p>
                            <p class="text-xs text-gray-500">{{ $announcement->created_at->diffForHumans() }}</p>
                        </div>
                        @if($announcement->is_urgent)
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Urgent</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No announcements yet.</p>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('hr.employees.index') }}" class="bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700 text-center">
            Manage Employees
        </a>
        <a href="{{ route('hr.announcements.create') }}" class="bg-green-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-700 text-center">
            Create Announcement
        </a>
        <a href="{{ route('hr.leaves.index') }}" class="bg-yellow-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-yellow-700 text-center">
            Leave Requests
        </a>
        <a href="{{ route('hr.payroll.create') }}" class="bg-purple-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-purple-700 text-center">
            Process Payroll
        </a>
    </div>
</div>
@endsection
