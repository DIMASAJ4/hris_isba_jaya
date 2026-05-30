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
        'created_by',
        'attendance_open',
        'attendance_opened_at',
        'attendance_closed_at'
    ];

    protected $casts = [
        'event_date' => 'date',
        'attendance_open' => 'boolean',
        'attendance_opened_at' => 'datetime',
        'attendance_closed_at' => 'datetime'
    ];

    protected static function booted()
    {
        static::creating(function ($event) {
            // Auto-generate event_code (BA-YYYY-XXX)
            // withTrashed() ensures deleted events' codes are never reused
            $year = date('Y');
            $prefix = "BA-{$year}-";

            // Find the highest sequence number already used this year
            $maxCode = static::withTrashed()
                ->where('event_code', 'like', "{$prefix}%")
                ->max('event_code');

            $nextNumber = 1;
            if ($maxCode) {
                $parts = explode('-', $maxCode);
                $lastNum = (int) end($parts);
                $nextNumber = $lastNum + 1;
            }

            $event->event_code = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

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

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function isAttendanceOpen(): bool
    {
        return $this->attendance_open && 
               (!$this->attendance_opened_at || $this->attendance_opened_at->isPast()) && 
               (!$this->attendance_closed_at || $this->attendance_closed_at->isFuture());
    }

    public function totalHadir(): int
    {
        return $this->attendances()->where('status', 'Hadir')->count();
    }

    public function totalTidakHadir(): int
    {
        return $this->attendances()->where('status', 'Tidak Hadir')->count();
    }

    public function totalIzin(): int
    {
        return $this->attendances()->where('status', 'Izin')->count();
    }

    public function totalSakit(): int
    {
        return $this->attendances()->where('status', 'Sakit')->count();
    }
}
