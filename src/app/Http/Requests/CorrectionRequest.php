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
            'start_time' => ['required', 'before:end_time'],
            'end_time' => ['required'],
        ];
    }

    public function messages()
    {
        return [

            'reason.required' =>
            '備考を記入してください',

            'start_time.required' =>
            '出勤時間を入力してください',

            'end_time.required' =>
            '退勤時間を入力してください',

            'start_time.before' =>
            '出勤時間もしくは退勤時間が不適切な値です',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $start = $this->start_time;
            $end = $this->end_time;

            foreach ($this->break_start ?? [] as $breakStart) {

                if (empty($breakStart)) {
                    continue;
                }

                if ($breakStart < $start || $breakStart > $end) {

                    $validator->errors()->add(
                        'break_start',
                        '休憩時間が不適切な値です'
                    );
                }
            }

            foreach ($this->break_end ?? [] as $breakEnd) {

                if (empty($breakEnd)) {
                    continue;
                }

                if ($breakEnd > $end) {

                    $validator->errors()->add(
                        'break_end',
                        '休憩時間もしくは退勤時間が不適切な値です'
                    );
                }
            }
        });
    }

}
