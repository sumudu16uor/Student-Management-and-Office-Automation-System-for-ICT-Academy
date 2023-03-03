<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
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
            'subjectName' => [
                'required',
                'string',
                Rule::unique('subjects')->where(function ($query) {
                    $query->where('subjectName', $this->subjectName)
                        ->where('categoryID', $this->categoryID);
                })->ignore($this->route('subject'), 'subjectID'),
                'min:4',
                'max:50'
            ],
            'medium' => ['required', Rule::in(['Sinhala', 'English', 'Tamil']), 'string', 'max:7'],
            'categoryID' => ['required', Rule::exists('categories', 'categoryID'), 'string', 'size:8']
        ];
    }
}
