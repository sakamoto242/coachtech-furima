<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest // 「FormRequest」まで正しく書く
{ // クラスの開始括弧を追加
    public function rules()
    {
        return [
            'start_time' => 'required|before:end_time',
            'end_time'   => 'required|after:start_time',
            'reason'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'start_time.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'reason.required'   => '備考を記入してください',
        ];
    }
} 