@extends('layouts.app')

@section('title', 'Add Employee - Uni-H-Pen HR')

@section('content')
<div class="mb-6">
    <a href="{{ route('hr.employees.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Back to Employees</a>
    <h1 class="text-3xl font-bold">Add Employee Record</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="{{ route('hr.employees.store') }}">
        @csrf

        <!-- User Selection -->
        <div class="mb-6">
            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Select Staff User *</label>
            <select name="user_id" id="user_id" required class="w-full border rounded-lg px-4 py-2 @error('user_id') border-red-500 @enderror">
                <option value="">-- Select User --</option>
                @foreach($availableUsers as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            @if($availableUsers->isEmpty())
                <p class="text-yellow-600 text-sm mt-1">No available users. All staff users already have employee records.</p>
            @endif
        </div>

        <!-- Employee Number -->
        <div class="mb-6">
            <label for="employee_number" class="block text-sm font-medium text-gray-700 mb-2">Employee Number</label>
            <input type="text" name="employee_number" id="employee_number" value="{{ old('employee_number') }}" 
                   placeholder="Auto-generated if left empty" 
                   class="w-full border rounded-lg px-4 py-2 @error('employee_number') border-red-500 @enderror">
            @error('employee_number')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Position and Department -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position *</label>
                <input type="text" name="position" id="position" value="{{ old('position') }}" required 
                       class="w-full border rounded-lg px-4 py-2 @error('position') border-red-500 @enderror">
                @error('position')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                <input type="text" name="department" id="department" value="{{ old('department') }}" required 
                       class="w-full border rounded-lg px-4 py-2 @error('department') border-red-500 @enderror">
                @error('department')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Hire Date and Employment Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">Hire Date *</label>
                <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date') }}" required 
                       class="w-full border rounded-lg px-4 py-2 @error('hire_date') border-red-500 @enderror">
                @error('hire_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="employment_status" class="block text-sm font-medium text-gray-700 mb-2">Employment Status *</label>
                <select name="employment_status" id="employment_status" required 
                        class="w-full border rounded-lg px-4 py-2 @error('employment_status') border-red-500 @enderror">
                    <option value="active" {{ old('employment_status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="on_leave" {{ old('employment_status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    <option value="terminated" {{ old('employment_status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
                @error('employment_status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Salary Rate -->
        <div class="mb-6">
            <label for="salary_rate" class="block text-sm font-medium text-gray-700 mb-2">Salary Rate (Monthly) *</label>
            <input type="number" name="salary_rate" id="salary_rate" value="{{ old('salary_rate') }}" step="0.01" min="0" required 
                   class="w-full border rounded-lg px-4 py-2 @error('salary_rate') border-red-500 @enderror">
            @error('salary_rate')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Government IDs -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Government Records</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="sss_number" class="block text-sm font-medium text-gray-700 mb-2">SSS Number</label>
                    <input type="text" name="sss_number" id="sss_number" value="{{ old('sss_number') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('sss_number') border-red-500 @enderror">
                    @error('sss_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="pagibig_number" class="block text-sm font-medium text-gray-700 mb-2">Pag-IBIG Number</label>
                    <input type="text" name="pagibig_number" id="pagibig_number" value="{{ old('pagibig_number') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('pagibig_number') border-red-500 @enderror">
                    @error('pagibig_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="philhealth_number" class="block text-sm font-medium text-gray-700 mb-2">PhilHealth Number</label>
                    <input type="text" name="philhealth_number" id="philhealth_number" value="{{ old('philhealth_number') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('philhealth_number') border-red-500 @enderror">
                    @error('philhealth_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" id="notes" rows="3" 
                      class="w-full border rounded-lg px-4 py-2 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
            @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                Create Employee Record
            </button>
            <a href="{{ route('hr.employees.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

