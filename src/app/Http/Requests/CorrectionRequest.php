<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CorrectionRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'requested_start_time' => 'required',
            'requested_end_time' => 'required|after:requested_start_time',
            'reason' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'requested_end_time.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'reason.required' => '備考を記入してください',
        ];
    }
}
