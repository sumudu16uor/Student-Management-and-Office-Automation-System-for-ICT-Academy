<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExamRequest extends FormRequest
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
            'exam' => ['required', 'string', 'min:5', 'max:100'],
            'totalMark' => ['nullable', 'integer'],
            'date' => ['required', 'date'],
            'classID' => ['required', Rule::exists('classes', 'classID'), 'string', 'size:8'],
            'subjectID' => ['required', Rule::exists('subjects', 'subjectID'), 'string', 'size:8'],
            'categoryID' => ['required', Rule::exists('categories', 'categoryID'), 'string', 'size:8'],
            'branchID' => ['required', Rule::exists('branches', 'branchID'), 'string', 'size:8']
        ];
    }
}
