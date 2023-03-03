<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStaffRequest extends FormRequest
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
            'title' => ['required', Rule::in(['Mr.', 'Miss.', 'Mrs.', 'Ms.']), 'string', 'max:5'],
            'firstName' => ['required', 'string', 'regex:/^[a-zA-Z\s\.]+$/i', 'min:4', 'max:50'],
            'lastName' => ['required', 'string', 'regex:/^[a-zA-Z\s\.]+$/i', 'min:4', 'max:50'],
            'nic' => ['required', 'string', 'alpha_num', 'min:10', 'max:12'],
            'dob' => ['required', 'date'],
            'sex' => ['required', Rule::in(['Male', 'Female', 'Other']), 'string', 'max:6'],
            'telNo' => ['required', 'string', 'size:10'],
            'address' => ['required', 'string', 'min:6', 'max:150'],
            'email' => ['required', 'email', 'max:50'],
            'status' => ['required', Rule::in(['Active', 'Deactivate']), 'string', 'max:10'],
            'joinedDate' => ['required', 'date'],
            'branchID' => ['required', Rule::exists('branches', 'branchID'), 'string', 'size:8']
        ];
    }
}
