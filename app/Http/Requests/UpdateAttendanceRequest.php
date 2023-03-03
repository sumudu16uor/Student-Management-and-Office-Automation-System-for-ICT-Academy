<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttendanceRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'studentID' => ['nullable', Rule::exists('students', 'studentID'), 'string', 'size:11'],
            'classID' => ['nullable', Rule::exists('classes', 'classID'), 'string', 'size:8'],
            'date' => ['required', 'date'],
            'time' => ['nullable', 'date_format:H:i'],
            'attendStatus' => ['required', Rule::in(['0', '1']), 'integer']
        ];
    }
}
