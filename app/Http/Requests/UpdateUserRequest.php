<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'username' => ['nullable',
                Rule::unique('users')->where(function ($query) {
                    $query->where('username', $this->userName);
                })->ignore($this->route('user'), 'userID'),
                'string',
                'min:5','max:50'
            ],
            'password' => ['nullable', 'string','min:8', 'confirmed'],
            'privilege' => ['nullable', Rule::in(['Administrator', 'Standard', 'Guess']), 'string', 'max:13'],
            'employeeID' => ['nullable', Rule::exists('employees', 'employeeID'), 'string', 'size:8'],
            'status' => ['nullable', Rule::in('Active', 'Deactivate'), 'string', 'max:10']
        ];
    }
}
