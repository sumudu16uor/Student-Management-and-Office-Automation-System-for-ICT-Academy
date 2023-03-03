<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParentsRequest extends FormRequest
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
            'title' => ['required', Rule::in(['Mr.', 'Mrs.']), 'string', 'max:5'],
            'parentName' => ['required', 'string', 'regex:/^[a-zA-Z\s\.]+$/i', 'min:4', 'max:50'],
            'parentType' => ['required', Rule::in(['Father', 'Mother', 'Guardian']), 'string', 'max:10'],
            'telNo' => ['required', 'string', 'size:10']
        ];
    }
}
