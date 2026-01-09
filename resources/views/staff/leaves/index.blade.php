@extends('layouts.app')

@section('title', 'Leave Requests - Uni-H-Pen Staff')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">My Leave Requests</h1>
    <a href="{{ route('staff.leaves.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700">
        + Request Leave
    </a>
</div>

<!-- Date Filter -->
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form method="GET" action="{{ route('staff.leaves.index') }}" class="flex gap-4 items-end">
        <div class="flex-1">
            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Filter by Date</label>
            <input type="date" name="date" id="date" value="{{ request('date') }}" 
                   class="w-full border rounded-lg px-4 py-2">
        </div>
        <div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                Filter
            </button>
        </div>
        @if(request('date'))
            <div>
                <a href="{{ route('staff.leaves.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">
                    Clear
                </a>
            </div>
        @endif
    </form>
</div>

<!-- Leave Requests Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold">Leave Request History</h2>
        @if(request('date'))
            <p class="text-sm text-gray-600 mt-1">Showing requests for: {{ \Carbon\Carbon::parse(request('date'))->format('F d, Y') }}</p>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($leaveRequests as $leave)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($leave->leave_type) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $leave->start_date->format('M d, Y') }}
                        <div class="text-xs text-gray-500">{{ $leave->start_date->format('l') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $leave->end_date->format('M d, Y') }}
                        <div class="text-xs text-gray-500">{{ $leave->end_date->format('l') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $leave->days_requested }} day(s)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($leave->status === 'approved')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                            @if($leave->approved_at)
                                <div class="text-xs text-gray-500 mt-1">{{ $leave->approved_at->format('M d, Y') }}</div>
                            @endif
                        @elseif($leave->status === 'rejected')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                            @if($leave->approved_at)
                                <div class="text-xs text-gray-500 mt-1">{{ $leave->approved_at->format('M d, Y') }}</div>
                            @endif
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ Str::limit($leave->reason, 50) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $leave->remarks ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        @if(request('date'))
                            No leave requests found for the selected date.
                        @else
                            No leave requests found. <a href="{{ route('staff.leaves.create') }}" class="text-blue-600 hover:text-blue-800">Create one now</a>.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gray-50 px-6 py-4">
        {{ $leaveRequests->links() }}
    </div>
</div>
@endsection

