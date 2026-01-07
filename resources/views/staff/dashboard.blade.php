@extends('layouts.app')

@section('title', 'Staff Dashboard - Winbreaker')

@section('content')
<h1 class="text-3xl font-bold mb-6">Staff Dashboard</h1>
<p class="text-gray-600 mb-8">Welcome back, {{ auth()->user()->name }}!</p>

<!-- Today's Attendance Card -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-2xl font-bold mb-4">Today's Attendance</h2>
    @if($todayAttendance)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600">Time In</p>
                <p class="text-xl font-bold text-green-600">{{ $todayAttendance->time_in ? date('h:i A', strtotime($todayAttendance->time_in)) : 'Not recorded' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Time Out</p>
                <p class="text-xl font-bold text-blue-600">{{ $todayAttendance->time_out ? date('h:i A', strtotime($todayAttendance->time_out)) : 'Not recorded' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                    @if($todayAttendance->status == 'present') bg-green-100 text-green-800
                    @elseif($todayAttendance->status == 'late') bg-yellow-100 text-yellow-800
                    @elseif($todayAttendance->status == 'absent') bg-red-100 text-red-800
                    @else bg-blue-100 text-blue-800
                    @endif">
                    {{ ucfirst($todayAttendance->status) }}
                </span>
            </div>
        </div>
        @if(!$todayAttendance->time_in)
            <form method="POST" action="{{ route('staff.attendance.timeIn') }}" class="mt-4">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                    Time In
                </button>
            </form>
        @elseif(!$todayAttendance->time_out)
            <form method="POST" action="{{ route('staff.attendance.timeOut') }}" class="mt-4">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Time Out
                </button>
            </form>
        @endif
    @else
        <form method="POST" action="{{ route('staff.attendance.timeIn') }}">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                Time In Now
            </button>
        </form>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Announcements -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Announcements</h2>
        @if($announcements->count() > 0)
            <div class="space-y-3">
                @foreach($announcements as $announcement)
                <div class="border-l-4 {{ $announcement->is_urgent ? 'border-red-500 bg-red-50' : 'border-blue-500 bg-blue-50' }} p-4 rounded">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold">{{ $announcement->title }}</p>
                            <p class="text-sm text-gray-700 mt-1">{{ $announcement->message }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $announcement->created_at->diffForHumans() }}</p>
                        </div>
                        @if($announcement->is_urgent)
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Urgent</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No announcements at the moment.</p>
        @endif
    </div>

    <!-- Leave Requests Status -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Leave Requests</h2>
        <div class="mb-4">
            <p class="text-sm text-gray-600">Pending: <span class="font-bold text-yellow-600">{{ $pendingLeaves }}</span></p>
        </div>
        @if($leaveRequests->count() > 0)
            <div class="space-y-2">
                @foreach($leaveRequests as $leave)
                <div class="flex justify-between items-center border-b pb-2">
                    <div>
                        <p class="font-semibold">{{ $leave->leave_type }}</p>
                        <p class="text-sm text-gray-600">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($leave->status == 'approved') bg-green-100 text-green-800
                        @elseif($leave->status == 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ ucfirst($leave->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No leave requests yet.</p>
        @endif
        <a href="{{ route('staff.leaves.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 text-sm">
            Request Leave
        </a>
    </div>
</div>

<!-- Quick Links -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4">Quick Links</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('staff.attendance.index') }}" class="bg-green-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-700 text-center">
            Attendance History
        </a>
        <a href="{{ route('staff.leaves.index') }}" class="bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700 text-center">
            Leave Requests
        </a>
        <a href="{{ route('staff.payroll.index') }}" class="bg-purple-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-purple-700 text-center">
            Payroll History
        </a>
    </div>
</div>
@endsection


