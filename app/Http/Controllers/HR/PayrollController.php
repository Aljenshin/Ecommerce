<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\PayrollItem;
use App\Models\PayrollRun;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index()
    {
        $payrollRuns = PayrollRun::with('processor')
            ->latest()
            ->paginate(15);

        return view('hr.payroll.index', compact('payrollRuns'));
    }

    public function create()
    {
        return view('hr.payroll.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'payroll_period' => ['required', 'string', 'max:255'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after:period_start'],
        ]);

        DB::beginTransaction();
        try {
            $payrollRun = PayrollRun::create([
                'payroll_period' => $request->payroll_period,
                'period_start' => $request->period_start,
                'period_end' => $request->period_end,
                'processed_by' => auth()->id(),
                'status' => 'draft',
            ]);

            // Get all active staff employees
            $employees = Employee::with('user')
                ->where('employment_status', 'active')
                ->get();

            foreach ($employees as $employee) {
                $this->calculatePayrollItem($payrollRun, $employee);
            }

            $payrollRun->update(['status' => 'processed', 'processed_at' => now()]);

            DB::commit();

            return redirect()->route('hr.payroll.show', $payrollRun->id)
                ->with('success', 'Payroll processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process payroll: ' . $e->getMessage());
        }
    }

    public function show(PayrollRun $payrollRun)
    {
        $payrollRun->load(['payrollItems.user', 'processor']);
        $payrollItems = $payrollRun->payrollItems()->with('user')->paginate(20);

        return view('hr.payroll.show', compact('payrollRun', 'payrollItems'));
    }

    private function calculatePayrollItem(PayrollRun $payrollRun, Employee $employee)
    {
        $periodStart = $payrollRun->period_start;
        $periodEnd = $payrollRun->period_end;

        // Get attendances for the period
        $attendances = Attendance::where('user_id', $employee->user_id)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->get();

        // Calculate totals
        $totalHours = $attendances->sum('total_hours');
        $totalLates = $attendances->where('status', 'late')->count();
        $totalAbsences = $attendances->where('status', 'absent')->count();
        $totalUndertimeMinutes = $attendances->sum('minutes_undertime');
        $totalOvertimeMinutes = $attendances->sum('minutes_overtime');

        // Base salary calculation (assuming monthly salary)
        $daysInPeriod = $periodStart->diffInDays($periodEnd) + 1;
        $workingDays = $daysInPeriod - $totalAbsences; // Exclude absences
        $dailyRate = $employee->salary_rate / 22; // Assuming 22 working days per month
        $baseSalary = $dailyRate * $workingDays;

        // Overtime pay (assuming 1.5x rate)
        $overtimeHours = $totalOvertimeMinutes / 60;
        $hourlyRate = $dailyRate / 8;
        $overtimePay = $overtimeHours * $hourlyRate * 1.5;

        // Deductions
        $lateDeduction = $totalLates * ($dailyRate * 0.01); // 1% per late
        $absentDeduction = $totalAbsences * $dailyRate;
        $undertimeDeduction = ($totalUndertimeMinutes / 60) * $hourlyRate;
        $leaveDeduction = 0; // Can be calculated based on leave requests

        $totalEarnings = $baseSalary + $overtimePay;
        $totalDeductions = $lateDeduction + $absentDeduction + $undertimeDeduction + $leaveDeduction;
        $netPay = $totalEarnings - $totalDeductions;

        PayrollItem::create([
            'payroll_run_id' => $payrollRun->id,
            'user_id' => $employee->user_id,
            'base_salary' => $baseSalary,
            'overtime_pay' => $overtimePay,
            'total_earnings' => $totalEarnings,
            'late_deduction' => $lateDeduction,
            'absent_deduction' => $absentDeduction,
            'undertime_deduction' => $undertimeDeduction,
            'leave_deduction' => $leaveDeduction,
            'total_deductions' => $totalDeductions,
            'total_hours_worked' => round($totalHours),
            'total_lates' => $totalLates,
            'total_absences' => $totalAbsences,
            'total_undertime_minutes' => $totalUndertimeMinutes,
            'total_overtime_minutes' => $totalOvertimeMinutes,
            'net_pay' => $netPay,
        ]);
    }
}
