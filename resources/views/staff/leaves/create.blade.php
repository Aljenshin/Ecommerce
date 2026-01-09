@extends('layouts.app')

@section('title', 'Request Leave - Uni-H-Pen Staff')

@section('content')
<div class="mb-6">
    <a href="{{ route('staff.leaves.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Back to Leave Requests</a>
    <h1 class="text-3xl font-bold">Request Leave</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="{{ route('staff.leaves.store') }}">
        @csrf

        <!-- Leave Type -->
        <div class="mb-6">
            <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">Leave Type *</label>
            <select name="leave_type" id="leave_type" required 
                    class="w-full border rounded-lg px-4 py-2 @error('leave_type') border-red-500 @enderror">
                <option value="">-- Select Leave Type --</option>
                <option value="vacation" {{ old('leave_type') == 'vacation' ? 'selected' : '' }}>Vacation</option>
                <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                <option value="emergency" {{ old('leave_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                <option value="personal" {{ old('leave_type') == 'personal' ? 'selected' : '' }}>Personal</option>
            </select>
            @error('leave_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Date Selection -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Select Leave Dates</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required 
                           min="{{ today()->format('Y-m-d') }}"
                           class="w-full border rounded-lg px-4 py-2 @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Select the first day of your leave</p>
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required 
                           min="{{ today()->format('Y-m-d') }}"
                           class="w-full border rounded-lg px-4 py-2 @error('end_date') border-red-500 @enderror">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Select the last day of your leave</p>
                </div>
            </div>
            <div class="mt-4 bg-blue-50 border-l-4 border-blue-500 p-4">
                <p class="text-sm text-blue-700">
                    <strong>Selected Dates:</strong>
                    <span id="date-range-display" class="font-semibold">Please select start and end dates</span>
                </p>
                <p class="text-xs text-blue-600 mt-1" id="days-calculated"></p>
            </div>
        </div>

        <!-- Reason -->
        <div class="mb-6">
            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason *</label>
            <textarea name="reason" id="reason" rows="4" required 
                      class="w-full border rounded-lg px-4 py-2 @error('reason') border-red-500 @enderror" 
                      placeholder="Please provide a reason for your leave request...">{{ old('reason') }}</textarea>
            @error('reason')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                Submit Leave Request
            </button>
            <a href="{{ route('staff.leaves.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const dateRangeDisplay = document.getElementById('date-range-display');
    const daysCalculated = document.getElementById('days-calculated');

    function updateDateDisplay() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

            const startFormatted = start.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            const endFormatted = end.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });

            dateRangeDisplay.textContent = `${startFormatted} to ${endFormatted}`;
            daysCalculated.textContent = `Total days requested: ${diffDays} day(s)`;

            // Update end date minimum to be at least start date
            endDateInput.min = startDate;
        } else if (startDate) {
            dateRangeDisplay.textContent = `Starting: ${new Date(startDate).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}`;
            daysCalculated.textContent = '';
            endDateInput.min = startDate;
        } else {
            dateRangeDisplay.textContent = 'Please select start and end dates';
            daysCalculated.textContent = '';
        }
    }

    startDateInput.addEventListener('change', updateDateDisplay);
    endDateInput.addEventListener('change', updateDateDisplay);
</script>
@endsection

