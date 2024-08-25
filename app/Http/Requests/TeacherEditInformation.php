<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use App\Http\Responces\Response;
//use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException ;

class TeacherEditInformation extends FormRequest
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
           // 'id'=>'int',
            'email' => 'nullable|email|unique:users,email',
            'first_name' => 'nullable|string|min:3',
            'last_name' => 'nullable|string|min:3',
            //'password' => 'nullable|confirmed|min:8|max:64',
            'gender' => 'nullable|in:male,female', // إضافة قاعدة التحقق من الصحة
            'date_of_birth' => 'nullable|date', // إضافة قاعدة التحقق من الصحة
            'phone_number' => 'nullable|numeric|digits:10', // إضافة قاعدة التحقق من الصحة
            'address' => 'nullable|string', // إضافة قاعدة التحقق من الصحة
            'details' => 'nullable|string', // إضافة قاعدة التحقق من الصحة (اختياري)
            'national_id'=>'nullable|numeric|digits:11|unique:teachers',
        ];
    }
    protected function failedValidation(Validator $validator)
{
    throw new ValidationException($validator,Response::Validation([],$validator->errors()));
}
}
