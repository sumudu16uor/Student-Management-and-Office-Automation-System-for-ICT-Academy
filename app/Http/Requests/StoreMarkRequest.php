<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMarkRequest extends FormRequest
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
            'examID' => ['required', Rule::exists('exams', 'examID'), 'string', 'size:8'],
            'studentID' => ['nullable', Rule::exists('students', 'studentID'), 'string', 'size:11'],
            'mark' => ['nullable', 'integer']
        ];
    }
}
