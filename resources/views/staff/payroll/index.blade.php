@extends('layouts.app')

@section('title', 'Payroll - Winbreaker Staff')

@section('content')
<h1 class="text-3xl font-bold mb-6">My Payroll</h1>

<!-- Payroll Summary -->
@if($payrollItems->count() > 0)
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-bold mb-4">Payroll Summary</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <p class="text-sm text-gray-600">Total Records</p>
            <p class="text-2xl font-bold text-blue-600">{{ $payrollItems->total() }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Total Net Pay</p>
            <p class="text-2xl font-bold text-green-600">₱{{ number_format($payrollItems->sum('net_pay'), 2) }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Average Net Pay</p>
            <p class="text-2xl font-bold text-purple-600">₱{{ number_format($payrollItems->avg('net_pay'), 2) }}</p>
        </div>
    </div>
</div>
@endif

<!-- Payroll History -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold">Payroll History</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payroll Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overtime</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deductions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Pay</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payrollItems as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->payrollRun->payroll_period ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($item->payrollRun)
                            {{ $item->payrollRun->period_start->format('M d') }} - {{ $item->payrollRun->period_end->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ₱{{ number_format($item->base_salary, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ₱{{ number_format($item->overtime_pay, 2) }}
                        @if($item->total_overtime_minutes > 0)
                            <div class="text-xs text-gray-500">({{ round($item->total_overtime_minutes / 60, 1) }}h)</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                        -₱{{ number_format($item->total_deductions, 2) }}
                        @if($item->total_lates > 0 || $item->total_absences > 0)
                            <div class="text-xs text-gray-500">
                                @if($item->total_lates > 0){{ $item->total_lates }} late(s) @endif
                                @if($item->total_absences > 0){{ $item->total_absences }} absent(s) @endif
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-lg font-semibold text-green-600">₱{{ number_format($item->net_pay, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('staff.payroll.show', $item) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No payroll records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gray-50 px-6 py-4">
        {{ $payrollItems->links() }}
    </div>
</div>
@endsection

