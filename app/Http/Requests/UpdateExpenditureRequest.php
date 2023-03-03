<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpenditureRequest extends FormRequest
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
            'expense' => ['required', 'string', 'min:3', 'max:100'],
            'expenseAmount' => ['required', 'string', 'regex:/^[\d]{1,6}[\.][\d]{0,2}$/i', 'max:9'],
            'date' => ['required', 'date'],
            'handlerStaffID' => ['required', Rule::exists('staff', 'staffID'), 'string', 'size:8'],
            'branchID' => ['required', Rule::exists('branches', 'branchID'), 'string', 'size:8']
        ];
    }
}
