<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAttendance extends Model
{
    protected $table = 'event_attendances';

    protected $fillable = [
        'event_id',
        'member_id',
        'status',
        'checked_in_at',
        'is_self_checkin',
        'note'
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'is_self_checkin' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
