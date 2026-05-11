<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'member_code', 'full_name', 'nim', 'gender', 
        'birth_place', 'birth_date', 'phone', 'email', 'address', 
        'photo', 'department_id', 'position_id', 'batch_year', 
        'university', 'status', 'joined_at'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'joined_at' => 'date',
        'status' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($member) {
            if (empty($member->member_code)) {
                $year = date('Y');
                $lastMember = self::where('member_code', 'like', "ISBA-$year-%")->latest()->first();
                $nextNumber = 1;
                if ($lastMember) {
                    $lastNumber = (int) explode('-', $lastMember->member_code)[2];
                    $nextNumber = $lastNumber + 1;
                }
                $member->member_code = "ISBA-$year-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(MemberStatusLog::class);
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::url($this->photo) : asset('images/default-avatar.png');
    }
}
