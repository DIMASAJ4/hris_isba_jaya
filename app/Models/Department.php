<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'color', 'is_active'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($department) {
            if (empty($department->slug)) {
                $department->slug = Str::slug($department->name);
            }
        });
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }
}
