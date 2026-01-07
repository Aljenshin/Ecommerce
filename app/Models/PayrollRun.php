<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollRun extends Model
{
    protected $fillable = [
        'payroll_period',
        'period_start',
        'period_end',
        'processed_by',
        'status',
        'notes',
        'processed_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'processed_at' => 'datetime',
    ];

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function payrollItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }
}
