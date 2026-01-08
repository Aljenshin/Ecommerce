@extends('layouts.app')

@section('title', 'Payroll Details - Winbreaker Staff')

@section('content')
<div class="mb-6">
    <a href="{{ route('staff.payroll.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Back to Payroll</a>
    <h1 class="text-3xl font-bold">Payroll Details</h1>
    <p class="text-gray-600">{{ $payrollItem->payrollRun->payroll_period ?? 'N/A' }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Period Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4">Period Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Payroll Period</p>
                    <p class="font-semibold">{{ $payrollItem->payrollRun->payroll_period ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Period Range</p>
                    <p class="font-semibold">
                        @if($payrollItem->payrollRun)
                            {{ $payrollItem->payrollRun->period_start->format('M d, Y') }} - {{ $payrollItem->payrollRun->period_end->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Processed At</p>
                    <p class="font-semibold">
                        {{ $payrollItem->payrollRun->processed_at ? $payrollItem->payrollRun->processed_at->format('M d, Y h:i A') : 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="font-semibold">
                        @if($payrollItem->payrollRun->status === 'processed')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Processed</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Earnings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4">Earnings</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Base Salary</span>
                    <span class="font-semibold">₱{{ number_format($payrollItem->base_salary, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Overtime Pay</span>
                    <span class="font-semibold">₱{{ number_format($payrollItem->overtime_pay, 2) }}</span>
                    @if($payrollItem->total_overtime_minutes > 0)
                        <span class="text-xs text-gray-500">({{ round($payrollItem->total_overtime_minutes / 60, 1) }} hours)</span>
                    @endif
                </div>
                <div class="border-t pt-3 flex justify-between items-center">
                    <span class="font-semibold text-lg">Total Earnings</span>
                    <span class="font-bold text-lg text-green-600">₱{{ number_format($payrollItem->total_earnings, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Deductions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4">Deductions</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Late Deduction</span>
                    <span class="font-semibold text-red-600">-₱{{ number_format($payrollItem->late_deduction, 2) }}</span>
                    @if($payrollItem->total_lates > 0)
                        <span class="text-xs text-gray-500">({{ $payrollItem->total_lates }} late(s))</span>
                    @endif
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Absent Deduction</span>
                    <span class="font-semibold text-red-600">-₱{{ number_format($payrollItem->absent_deduction, 2) }}</span>
                    @if($payrollItem->total_absences > 0)
                        <span class="text-xs text-gray-500">({{ $payrollItem->total_absences }} absent(s))</span>
                    @endif
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Undertime Deduction</span>
                    <span class="font-semibold text-red-600">-₱{{ number_format($payrollItem->undertime_deduction, 2) }}</span>
                    @if($payrollItem->total_undertime_minutes > 0)
                        <span class="text-xs text-gray-500">({{ round($payrollItem->total_undertime_minutes / 60, 1) }} hours)</span>
                    @endif
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Leave Deduction</span>
                    <span class="font-semibold text-red-600">-₱{{ number_format($payrollItem->leave_deduction, 2) }}</span>
                </div>
                <div class="border-t pt-3 flex justify-between items-center">
                    <span class="font-semibold text-lg">Total Deductions</span>
                    <span class="font-bold text-lg text-red-600">₱{{ number_format($payrollItem->total_deductions, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Net Pay -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <h2 class="text-xl font-bold mb-2">Net Pay</h2>
            <p class="text-4xl font-bold">₱{{ number_format($payrollItem->net_pay, 2) }}</p>
        </div>

        <!-- Summary -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Summary</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Hours Worked</span>
                    <span class="font-semibold">{{ $payrollItem->total_hours_worked ?? 0 }} hours</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Lates</span>
                    <span class="font-semibold">{{ $payrollItem->total_lates }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Absences</span>
                    <span class="font-semibold">{{ $payrollItem->total_absences }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Overtime Hours</span>
                    <span class="font-semibold">{{ round($payrollItem->total_overtime_minutes / 60, 1) }} hours</span>
                </div>
            </div>
        </div>

        @if($payrollItem->notes)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Notes</h2>
            <p class="text-sm text-gray-700">{{ $payrollItem->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

