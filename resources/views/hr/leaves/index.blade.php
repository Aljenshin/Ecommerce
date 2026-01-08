@extends('layouts.app')

@section('title', 'Leave Requests - Winbreaker HR')

@section('content')
<h1 class="text-3xl font-bold mb-6">Leave Requests</h1>

<!-- Pending Leaves Section -->
@if($pendingLeaves->count() > 0)
<div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-bold mb-4 text-yellow-800">Pending Approval ({{ $pendingLeaves->count() }})</h2>
    <div class="space-y-4">
        @foreach($pendingLeaves as $leave)
        <div class="bg-white rounded-lg p-4 border border-yellow-200">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="font-bold text-lg">{{ $leave->user->name }}</h3>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                    </div>
                    <p class="text-gray-600 mb-2">
                        <span class="font-semibold">{{ ucfirst($leave->leave_type) }}</span> - 
                        {{ $leave->days_requested }} day(s)
                    </p>
                    <p class="text-sm text-gray-500 mb-2">
                        {{ $leave->start_date->format('M d, Y') }} to {{ $leave->end_date->format('M d, Y') }}
                    </p>
                    @if($leave->reason)
                        <p class="text-sm text-gray-700 mt-2"><strong>Reason:</strong> {{ $leave->reason }}</p>
                    @endif
                </div>
                <div class="flex gap-2 ml-4">
                    <form method="POST" action="{{ route('hr.leaves.approve', $leave->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 text-sm">
                            Approve
                        </button>
                    </form>
                    <form method="POST" action="{{ route('hr.leaves.reject', $leave->id) }}" class="inline" onsubmit="return confirm('Reject this leave request?');">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text" name="remarks" placeholder="Reason for rejection" required 
                                   class="text-sm border rounded px-2 py-1 w-40">
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 text-sm">
                                Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- All Leave Requests -->
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">All Leave Requests</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved By</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($leaveRequests as $leave)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $leave->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $leave->user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst($leave->leave_type) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $leave->days_requested }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($leave->status === 'approved')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                        @elseif($leave->status === 'rejected')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $leave->approver ? $leave->approver->name : 'N/A' }}
                        @if($leave->approved_at)
                            <div class="text-xs text-gray-500">{{ $leave->approved_at->format('M d, Y') }}</div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No leave requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $leaveRequests->links() }}
    </div>
</div>
@endsection

