<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HRAnnouncement extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'message',
        'type',
        'audience',
        'target_department',
        'is_urgent',
        'effective_date',
    ];

    protected $casts = [
        'is_urgent' => 'boolean',
        'effective_date' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
