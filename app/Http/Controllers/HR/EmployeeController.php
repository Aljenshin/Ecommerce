<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('hr.employees.index', compact('employees'));
    }

    public function create()
    {
        // Get users who are staff/uploader but don't have employee records yet
        $availableUsers = User::whereIn('role', [User::ROLE_STAFF, User::ROLE_UPLOADER])
            ->whereDoesntHave('employee')
            ->get();

        return view('hr.employees.create', compact('availableUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id', 'unique:employees,user_id'],
            'employee_number' => ['nullable', 'string', 'max:255', 'unique:employees,employee_number'],
            'position' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'hire_date' => ['required', 'date'],
            'salary_rate' => ['required', 'numeric', 'min:0'],
            'employment_status' => ['required', 'in:active,on_leave,terminated'],
            'sss_number' => ['nullable', 'string', 'max:255'],
            'pagibig_number' => ['nullable', 'string', 'max:255'],
            'philhealth_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $data = $request->all();
        
        // Auto-generate employee number if not provided
        if (empty($data['employee_number'])) {
            $data['employee_number'] = 'EMP-' . strtoupper(Str::random(8));
        }

        Employee::create($data);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee record created successfully.');
    }

    public function edit(Employee $employee)
    {
        return view('hr.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_number' => ['nullable', 'string', 'max:255', 'unique:employees,employee_number,' . $employee->id],
            'position' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'hire_date' => ['required', 'date'],
            'salary_rate' => ['required', 'numeric', 'min:0'],
            'employment_status' => ['required', 'in:active,on_leave,terminated'],
            'sss_number' => ['nullable', 'string', 'max:255'],
            'pagibig_number' => ['nullable', 'string', 'max:255'],
            'philhealth_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $employee->update($request->all());

        return redirect()->route('hr.employees.index')
            ->with('success', 'Employee record updated successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load('user');
        $leaveRequests = $employee->user->leaveRequests()->latest()->take(5)->get();
        $recentAttendances = $employee->user->attendances()->latest()->take(10)->get();
        $payrollHistory = $employee->user->payrollItems()->with('payrollRun')->latest()->take(5)->get();

        return view('hr.employees.show', compact('employee', 'leaveRequests', 'recentAttendances', 'payrollHistory'));
    }
}
