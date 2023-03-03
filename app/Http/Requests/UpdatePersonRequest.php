<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonRequest extends FormRequest
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
            'firstName' => ['required', 'string', 'regex:/^[a-zA-Z\s\.]+$/i', 'min:4', 'max:50'],
            'lastName' => ['required', 'string', 'regex:/^[a-zA-Z\s\.]+$/i', 'min:4', 'max:50'],
            'dob' => ['required', 'date'],
            'sex' => ['required', Rule::in(['Male', 'Female', 'Other']), 'string', 'max:6'],
            'telNo' => ['required', 'string', 'size:10'],
            'address' => ['required', 'string', 'min:6', 'max:150'],
            'email' => ['nullable', 'email', 'max:50'],
            'status' => ['required', Rule::in(['Active', 'Past', 'Deactivate']), 'string', 'max:10'],
            'joinedDate' => ['required', 'date']
        ];
    }
}
