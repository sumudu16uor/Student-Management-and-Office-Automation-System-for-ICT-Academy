<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnrollmentRequest extends FormRequest
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
            'studentID' => ['required', Rule::exists('students', 'studentID')],
            'classID' => ['required', Rule::exists('classes', 'classID')],
            'paymentStatus' => ['nullable', Rule::in(['-1', '0', '1']), 'integer'],
            'enrolledDate' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['0', '1']), 'integer']
        ];
    }
}
