<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException ;
use App\Http\Responces\Response;


class ParentEditInformation extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id'=>'int',
            'father_first_name'=>'|string|min:3|max:50|nullable',
            'father_last_name'=>'|string|min:3|max:50|nullable',
            'father_phone_number'=>'nullable|numeric|digits:10',
            'mother_first_name'=>'|string|min:3|max:50|nullable',
            'mother_last_name'=>'|string|min:3|max:50|nullable',
            'mother_phone_number'=>'|numeric|digits:10|nullable',
            'national_id'=>'nullable|numeric|digits:11|unique:parentts',
            'email' => '|email|unique:users,email|nullable',
            //'password' => 'nullable|confirmed|min:8|max:64'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator,Response::Validation([],$validator->errors()));
    }
}
