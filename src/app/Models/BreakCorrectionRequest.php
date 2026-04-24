<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakCorrectionRequest extends Model
{
    protected $fillable = [
        'stamp_correction_request_id',
        'breaktime_id',
        'before_start_time',
        'after_start_time',
        'before_end_time',
        'after_end_time',
        'reason',
        'status',
    ];

    public function stampCorrectionRequest()
    {
        return $this->belongsTo(StampCorrectionRequest::class);
    }

    public function breakTime()
    {
        return $this->belongsTo(Breaktime::class, 'breaktime_id');
    }
}
