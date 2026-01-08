@extends('layouts.app')

@section('title', 'Process Payroll - Winbreaker HR')

@section('content')
<div class="mb-6">
    <a href="{{ route('hr.payroll.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Back to Payroll</a>
    <h1 class="text-3xl font-bold">Process Payroll</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="{{ route('hr.payroll.store') }}">
        @csrf

        <!-- Payroll Period -->
        <div class="mb-6">
            <label for="payroll_period" class="block text-sm font-medium text-gray-700 mb-2">Payroll Period Name *</label>
            <input type="text" name="payroll_period" id="payroll_period" value="{{ old('payroll_period') }}" required 
                   placeholder="e.g., January 2026 - First Half" 
                   class="w-full border rounded-lg px-4 py-2 @error('payroll_period') border-red-500 @enderror">
            @error('payroll_period')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Period Dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="period_start" class="block text-sm font-medium text-gray-700 mb-2">Period Start *</label>
                <input type="date" name="period_start" id="period_start" value="{{ old('period_start') }}" required 
                       class="w-full border rounded-lg px-4 py-2 @error('period_start') border-red-500 @enderror">
                @error('period_start')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="period_end" class="block text-sm font-medium text-gray-700 mb-2">Period End *</label>
                <input type="date" name="period_end" id="period_end" value="{{ old('period_end') }}" required 
                       class="w-full border rounded-lg px-4 py-2 @error('period_end') border-red-500 @enderror">
                @error('period_end')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <p class="text-sm text-blue-700">
                <strong>Note:</strong> This will process payroll for all active employees based on their attendance records for the selected period. 
                The system will automatically calculate base salary, overtime pay, and deductions (lateness, absences, undertime).
            </p>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                Process Payroll
            </button>
            <a href="{{ route('hr.payroll.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

