<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_run_id',
        'user_id',
        'base_salary',
        'overtime_pay',
        'total_earnings',
        'late_deduction',
        'absent_deduction',
        'undertime_deduction',
        'leave_deduction',
        'total_deductions',
        'total_hours_worked',
        'total_lates',
        'total_absences',
        'total_undertime_minutes',
        'total_overtime_minutes',
        'net_pay',
        'notes',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'late_deduction' => 'decimal:2',
        'absent_deduction' => 'decimal:2',
        'undertime_deduction' => 'decimal:2',
        'leave_deduction' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
