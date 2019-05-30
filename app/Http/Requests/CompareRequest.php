<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompareRequest extends FormRequest
{
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
            'first_student'=>'required|string|min:3',
            'second_student'=>'required|string|min:3',
            'first_file'=>'required|file|mimes:txt',
            'second_file'=>'required|file|mimes:txt'
        ];
    }
}
