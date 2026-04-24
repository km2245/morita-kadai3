<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CorrectionRequest extends FormRequest
{
    public function authorize()
    {
        // ログインユーザーなら許可
        return true;
    }

    public function rules()
    {
        return [
            // 備考は必須入力
            'reason' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            // 未入力時のメッセージ
            'reason.required' => '備考を記入してください',
        ];
    }
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
