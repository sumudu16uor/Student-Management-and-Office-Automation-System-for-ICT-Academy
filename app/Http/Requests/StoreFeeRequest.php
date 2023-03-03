<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeeRequest extends FormRequest
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
            'paidAmount' => ['nullable', 'string', 'regex:/^[\d]{1,5}[\.][\d]{0,2}$/i', 'max:8'],
            'date' => ['nullable', 'date'],
            'paidStatus' => ['nullable', Rule::in(['P', 'A']), 'string'],
            'handlerStaffID' => ['required', Rule::exists('staff', 'staffID'), 'string', 'size:8'],
            'branchID' => ['required', Rule::exists('branches', 'branchID'), 'string', 'size:8'],
            'paymentStatus' => ['nullable', Rule::notIn(['-1', '0']), 'integer'],
            'amounts.*.paidAmount' => ['nullable', 'string', 'regex:/^[\d]{1,5}[\.][\d]{0,2}$/i', 'max:8'],
            'classes.*.classID' => ['nullable', Rule::exists('classes', 'classID'), 'string', 'size:8'],
            'classes.*.paymentStatus' => ['nullable', Rule::notIn(['-1', '0']), 'integer'],
            'classes.*.amounts.*.paidAmount' => ['nullable', 'string', 'regex:/^[\d]{1,5}[\.][\d]{0,2}$/i', 'max:8']
        ];
    }
}
