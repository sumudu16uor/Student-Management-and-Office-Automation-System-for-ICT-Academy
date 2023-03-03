<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFeeRequest extends FormRequest
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
            'studentID' => ['required', Rule::exists('students', 'studentID'), 'string', 'size:11'],
            'classID' => ['required', Rule::exists('classes', 'classID'), 'string', 'size:8'],
            'paidAmount' => ['required', 'string', 'regex:/^[\d]{1,5}[\.][\d]{0,2}$/i', 'max:8'],
            'date' => ['required', 'date'],
            'paidStatus' => ['required', Rule::in(['P', 'A']), 'string'],
            'handlerStaffID' => ['required', Rule::exists('staff', 'staffID'), 'string', 'size:8'],
            'branchID' => ['required', Rule::exists('branches', 'branchID'), 'string', 'size:8']
        ];
    }
}
