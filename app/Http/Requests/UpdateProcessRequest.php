<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProcessRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'processType' => ['nullable', Rule::in(['month_end', 'year_end', 'ol_batch_end', 'al_batch_end', 'clear_login']), 'string', 'max:20'],
            'handlerStaffID' => ['required', Rule::exists('staff', 'staffID'), 'string', 'size:8'],
            'branchID' => ['required', Rule::exists('branches', 'branchID'), 'string', 'size:8']
        ];
    }
}
