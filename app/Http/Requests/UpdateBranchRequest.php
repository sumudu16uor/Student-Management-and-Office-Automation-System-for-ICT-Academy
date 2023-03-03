<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
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
            'branchName' => ['required', 'string', 'min:4', 'max:50'],
            'telNo' => ['required', 'string', 'size:10'],
            'address' => ['required', 'string', 'min:6', 'max:150'],
            'noOfRooms' => ['required', 'numeric']
        ];
    }
}
