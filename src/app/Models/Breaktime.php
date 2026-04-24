<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breaktime extends Model
{
    protected $fillable = [
        'attendance_id',
        'start_time',
        'end_time',
    ];
    
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function breakCorrectionRequests()
    {
        return $this->hasMany(BreakCorrectionRequest::class, 'breaktime_id');
    }
}
