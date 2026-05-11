<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_code',
        'title',
        'event_date',
        'location',
        'description',
        'notes',
        'status',
        'created_by'
    ];

    protected static function booted()
    {
        static::creating(function ($event) {
            // Auto-generate event_code (BA-YYYY-XXX)
            $year = date('Y');
            $latestEvent = static::whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 1;
            if ($latestEvent) {
                $lastCode = explode('-', $latestEvent->event_code);
                $nextNumber = (int)end($lastCode) + 1;
            }

            $event->event_code = sprintf('BA-%s-%s', $year, str_pad($nextNumber, 3, '0', STR_PAD_LEFT));
            
            // Auto-set created_by
            if (!$event->created_by) {
                $event->created_by = Auth::id();
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
