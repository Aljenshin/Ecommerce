@extends('layouts.app')

@section('title', 'Attendance - Winbreaker Staff')

@section('content')
<h1 class="text-3xl font-bold mb-6">My Attendance</h1>

<!-- Time In/Out Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-bold mb-4">Time In/Out</h2>
    @php
        $today = today();
        $todayAttendance = \App\Models\Attendance::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->first();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <p class="text-sm text-gray-600 mb-2">Today's Date</p>
            <p class="text-lg font-semibold">{{ $today->format('F d, Y (l)') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-2">Current Time</p>
            <p class="text-lg font-semibold" id="current-time">{{ now()->format('h:i:s A') }}</p>
        </div>
    </div>

    @if($todayAttendance)
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Time In</p>
                    <p class="text-lg font-semibold">
                        {{ $todayAttendance->time_in ? \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A') : 'Not recorded' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Time Out</p>
                    <p class="text-lg font-semibold">
                        {{ $todayAttendance->time_out ? \Carbon\Carbon::parse($todayAttendance->time_out)->format('h:i A') : 'Not recorded' }}
                    </p>
                </div>
            </div>
            @if($todayAttendance->status)
                <div class="mt-2">
                    <p class="text-sm text-gray-600">Status</p>
                    @if($todayAttendance->status === 'present')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                    @elseif($todayAttendance->status === 'late')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Late</span>
                    @elseif($todayAttendance->status === 'overtime')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Overtime</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($todayAttendance->status) }}</span>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <div class="flex gap-4">
        @if(!$todayAttendance || !$todayAttendance->time_in)
            <form method="POST" action="{{ route('staff.attendance.timeIn') }}">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                    Time In
                </button>
            </form>
        @endif

        @if($todayAttendance && $todayAttendance->time_in && !$todayAttendance->time_out)
            <form method="POST" action="{{ route('staff.attendance.timeOut') }}">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700">
                    Time Out
                </button>
            </form>
        @endif
    </div>
</div>

<!-- Date Filter -->
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form method="GET" action="{{ route('staff.attendance.index') }}" class="flex gap-4 items-end">
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
                <a href="{{ route('staff.attendance.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">
                    Clear
                </a>
            </div>
        @endif
    </form>
</div>

<!-- Attendance History -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold">Attendance History</h2>
        @if(request('date'))
            <p class="text-sm text-gray-600 mt-1">Showing records for: {{ \Carbon\Carbon::parse(request('date'))->format('F d, Y') }}</p>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($attendances as $attendance)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $attendance->date->format('M d, Y') }}
                        <div class="text-xs text-gray-500">{{ $attendance->date->format('l') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : 'N/A' }}
                        @if($attendance->minutes_late > 0)
                            <div class="text-xs text-red-600">({{ $attendance->minutes_late }}m late)</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : 'N/A' }}
                        @if($attendance->minutes_undertime > 0)
                            <div class="text-xs text-yellow-600">({{ $attendance->minutes_undertime }}m undertime)</div>
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
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $attendance->notes ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        @if(request('date'))
                            No attendance records found for the selected date.
                        @else
                            No attendance records found.
                        @endif
                    </td>
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

<script>
    // Update current time every second
    setInterval(function() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
        document.getElementById('current-time').textContent = timeString;
    }, 1000);
</script>
@endsection

