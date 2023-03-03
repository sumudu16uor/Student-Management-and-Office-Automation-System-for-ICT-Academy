<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'email' => ['required',
                Rule::exists('people')->where(function ($query) {
                    return $query->where('personID', $this->employeeID);
                }),
                Rule::unique('users', 'username'),
                'email',
                'min:5',
                'max:50'
            ],
            'password' => ['required', 'string', 'size:13', 'confirmed'],
            'privilege' => ['required', Rule::in(['Administrator', 'Standard', 'Guess']), 'string', 'max:13'],
            'employeeID' => ['required', Rule::exists('employees', 'employeeID'), 'string', 'size:8'],
            'status' => ['required', Rule::in('Active', 'Deactivate'), 'string', 'max:10']
        ];
    }
}
