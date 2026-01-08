@extends('layouts.app')

@section('title', 'Attendance Management - Winbreaker HR')

@section('content')
<h1 class="text-3xl font-bold mb-6">Attendance Management</h1>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form method="GET" action="{{ route('hr.attendance.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Staff Member</label>
            <select name="user_id" id="user_id" class="w-full border rounded-lg px-4 py-2">
                <option value="">All Staff</option>
                @foreach($staff as $s)
                    <option value="{{ $s->id }}" {{ request('user_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
            <input type="date" name="date" id="date" value="{{ request('date') }}" 
                   class="w-full border rounded-lg px-4 py-2">
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 w-full">
                Filter
            </button>
        </div>
    </form>
    @if(request('user_id') || request('date'))
        <div class="mt-4">
            <a href="{{ route('hr.attendance.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Clear Filters</a>
        </div>
    @endif
</div>

<!-- Attendance Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($attendances as $attendance)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $attendance->user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : 'N/A' }}
                        @if($attendance->minutes_late > 0)
                            <span class="text-red-600 text-xs">({{ $attendance->minutes_late }}m late)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : 'N/A' }}
                        @if($attendance->minutes_undertime > 0)
                            <span class="text-yellow-600 text-xs">({{ $attendance->minutes_undertime }}m undertime)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($attendance->status === 'present')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                        @elseif($attendance->status === 'late')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Late</span>
                        @elseif($attendance->status === 'absent')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Absent</span>
                        @elseif($attendance->status === 'undertime')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Undertime</span>
                        @elseif($attendance->status === 'overtime')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Overtime</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($attendance->status) }}</span>
                        @endif
                        @if($attendance->minutes_overtime > 0)
                            <div class="text-xs text-blue-600 mt-1">+{{ $attendance->minutes_overtime }}m OT</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->total_hours ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('hr.attendance.edit', $attendance) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No attendance records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gray-50 px-6 py-4">
        {{ $attendances->links() }}
    </div>
</div>
@endsection

