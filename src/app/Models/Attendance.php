<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breaks()
    {
        return $this->hasMany(Breaktime::class);
    }

    public function stampCorrectionRequests()
    {
        return $this->hasMany(StampCorrectionRequest::class);
    }
    public function adminRequestList()
    {
        // 全ユーザーの修正申請一覧を取得
        $requests = StampCorrectionRequest::with(['attendance', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 管理者用申請一覧画面へ渡す
        return view('admin.request-list', compact('requests'));
    }
}
