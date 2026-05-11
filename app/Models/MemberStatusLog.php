<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberStatusLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['member_id', 'old_status', 'new_status', 'changed_by', 'note', 'created_at'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
