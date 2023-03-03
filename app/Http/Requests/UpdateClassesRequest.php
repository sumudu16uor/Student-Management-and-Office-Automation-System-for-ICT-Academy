<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassesRequest extends FormRequest
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
            'className' => ['required', 'string', 'regex:/^[a-zA-Z\s\d\.\-\:\(\)\/\_]+$/i', 'min:5', 'max:100'],
            'day' => ['required', Rule::in(['Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']), 'string', 'max:10'],
            'startTime' => ['required', 'date_format:H:i'],
            'endTime' => ['required', 'date_format:H:i', 'after:startTime'],
            'grade' => ['required', 'string', 'min:1', 'max:5'],
            'room' => ['required', 'string', 'min:4', 'max:15'],
            'classFee' => ['required', 'string', 'regex:/^[\d]{1,5}[\.][\d]{0,2}$/i', 'max:8'],
            'feeType' => ['required', Rule::in(['Daily', 'Monthly']), 'string', 'max:8'],
            'status' => ['required', Rule::in(['Active', 'Deactivate']), 'string', 'max:10'],
            'subjectID' => ['required', Rule::exists('subjects', 'subjectID'), 'string', 'size:8'],
            'categoryID' => ['required', Rule::exists('categories', 'categoryID'), 'string', 'size:8'],
            'teacherID' => ['required', Rule::exists('teachers', 'teacherID'), 'string', 'size:8'],
            'branchID' => ['required', Rule::exists('branches', 'branchID'), 'string', 'size:8']
        ];
    }
}
