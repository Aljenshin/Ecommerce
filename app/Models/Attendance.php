<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'status',
        'minutes_late',
        'minutes_undertime',
        'minutes_overtime',
        'total_hours',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
