<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException ;
use App\Http\Responces\Response;

class ParentSignupRequest extends FormRequest
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
            'father_first_name'=>'required|string|min:3|max:50|',
            'father_last_name'=>'required|string|min:3|max:50|',
            'father_phone_number'=>'required|numeric|digits:10',
            'mother_first_name'=>'required|string|min:3|max:50|',
            'mother_last_name'=>'required|string|min:3|max:50|',
            'mother_phone_number'=>'required|numeric|digits:10',
            'national_id'=>'required|numeric|digits:11|unique:parentts',
            'email' => 'required|email|unique:users,email',
        ];
    }
    protected function failedValidation(Validator $validator)
{
    throw new ValidationException($validator,Response::Validation([],$validator->errors()));
}
}
