@extends('layouts.app')

@section('title', 'Payroll Details - Winbreaker HR')

@section('content')
<div class="mb-6">
    <a href="{{ route('hr.payroll.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Back to Payroll</a>
    <h1 class="text-3xl font-bold">Payroll Details</h1>
    <p class="text-gray-600">{{ $payrollRun->payroll_period }}</p>
    <p class="text-sm text-gray-500">
        {{ $payrollRun->period_start->format('M d, Y') }} - {{ $payrollRun->period_end->format('M d, Y') }}
    </p>
</div>

<!-- Payroll Summary -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-bold mb-4">Payroll Summary</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <p class="text-sm text-gray-600">Status</p>
            <p class="text-lg font-semibold">
                @if($payrollRun->status === 'processed')
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Processed</span>
                @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                @endif
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Total Employees</p>
            <p class="text-lg font-semibold text-blue-600">{{ $payrollItems->total() }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Total Net Pay</p>
            <p class="text-lg font-semibold text-green-600">₱{{ number_format($payrollItems->sum('net_pay'), 2) }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Processed By</p>
            <p class="text-lg font-semibold">{{ $payrollRun->processor->name ?? 'N/A' }}</p>
        </div>
    </div>
</div>

<!-- Payroll Items Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overtime</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deductions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Pay</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payrollItems as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $item->user->email }}</div>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="text-red-600">-₱{{ number_format($item->total_deductions, 2) }}</div>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->total_hours_worked ?? 'N/A' }}h
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No payroll items found.</td>
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

