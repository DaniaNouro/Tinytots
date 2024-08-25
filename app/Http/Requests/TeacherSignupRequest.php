<?php

namespace App\Http\Requests;

use App\Http\Responces\Response;
//use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException ;

class TeacherSignupRequest extends FormRequest
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
        'email' => 'required|email|unique:users,email',
        'first_name' => 'required|string|min:4',
        'last_name' => 'required|string|min:4',
        //'password' => 'required|confirmed|min:8|max:64',
        'gender' => 'required|in:male,female,Male,Female', // 
        'date_of_birth' => 'required|date', //
        'phone_number' => 'required|numeric', // 
        'address' => 'required|string', // 
        'details' => 'nullable|string', //
        'national_id'=>'required|numeric|digits:11|unique:teachers',
        ];
    }

   
protected function failedValidation(Validator $validator)
{
    throw new ValidationException($validator,Response::Validation([],$validator->errors()));
}
}
//regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,64}$/