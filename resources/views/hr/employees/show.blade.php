@extends('layouts.app')

@section('title', 'Employee Details - Uni-H-Pen HR')

@section('content')
<div class="mb-6">
    <a href="{{ route('hr.employees.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Back to Employees</a>
    <h1 class="text-3xl font-bold">Employee Details: {{ $employee->user->name }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4">Basic Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Name</p>
                    <p class="font-semibold">{{ $employee->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-semibold">{{ $employee->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Employee Number</p>
                    <p class="font-semibold">{{ $employee->employee_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Position</p>
                    <p class="font-semibold">{{ $employee->position }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Department</p>
                    <p class="font-semibold">{{ $employee->department }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Hire Date</p>
                    <p class="font-semibold">{{ $employee->hire_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Employment Status</p>
                    @if($employee->employment_status === 'active')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @elseif($employee->employment_status === 'on_leave')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">On Leave</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Terminated</span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-600">Salary Rate</p>
                    <p class="font-semibold">₱{{ number_format($employee->salary_rate, 2) }}/month</p>
                </div>
            </div>
        </div>

        <!-- Government Records -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4">Government Records</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">SSS Number</p>
                    <p class="font-semibold">{{ $employee->sss_number ?: 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pag-IBIG Number</p>
                    <p class="font-semibold">{{ $employee->pagibig_number ?: 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">PhilHealth Number</p>
                    <p class="font-semibold">{{ $employee->philhealth_number ?: 'N/A' }}</p>
                </div>
            </div>
        </div>

        @if($employee->notes)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4">Notes</h2>
            <p class="text-gray-700">{{ $employee->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Actions</h2>
            <div class="space-y-2">
                <a href="{{ route('hr.employees.edit', $employee) }}" class="block w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 text-center">
                    Edit Employee
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Quick Stats</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Leave Requests</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $leaveRequests->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Recent Attendances</p>
                    <p class="text-2xl font-bold text-green-600">{{ $recentAttendances->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Payroll Records</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $payrollHistory->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Leave Requests -->
@if($leaveRequests->count() > 0)
<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4">Recent Leave Requests</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($leaveRequests as $leave)
                <tr>
                    <td class="px-4 py-3 text-sm">{{ ucfirst($leave->leave_type) }}</td>
                    <td class="px-4 py-3 text-sm">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-sm">{{ $leave->days_requested }}</td>
                    <td class="px-4 py-3">
                        @if($leave->status === 'approved')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                        @elseif($leave->status === 'rejected')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Recent Attendances -->
@if($recentAttendances->count() > 0)
<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4">Recent Attendance</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time In</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time Out</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($recentAttendances as $attendance)
                <tr>
                    <td class="px-4 py-3 text-sm">{{ $attendance->date->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-sm">{{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : 'N/A' }}</td>
                    <td class="px-4 py-3 text-sm">{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : 'N/A' }}</td>
                    <td class="px-4 py-3">
                        @if($attendance->status === 'present')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                        @elseif($attendance->status === 'late')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Late</span>
                        @elseif($attendance->status === 'absent')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Absent</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($attendance->status) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $attendance->total_hours ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

