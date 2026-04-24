<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampCorrectionRequest extends Model
{
    protected $fillable = [
        'user_id',
        'attendance_id',
        'before_start_time',
        'after_start_time',
        'before_end_time',
        'after_end_time',
        'reason',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function breakCorrectionRequests()
    {
        return $this->hasMany(BreakCorrectionRequest::class);
    }
}
