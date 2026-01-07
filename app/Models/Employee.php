<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'employee_number',
        'position',
        'department',
        'hire_date',
        'salary_rate',
        'employment_status',
        'sss_number',
        'pagibig_number',
        'philhealth_number',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
