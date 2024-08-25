<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentEditInformation extends FormRequest
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

    public function rules()
    {
        return [
            'id'=>'int',
            'email' => 'nullable|email|exists:users,email',
            'first_name' => 'nullable|string|min:3',
            'last_name' => 'nullable|string|min:3',
            'gender' => 'nullable|in:male,female', 
            'date_of_birth' => 'nullable|date', 
            'address' => 'nullable|string', 
            'deatails' => 'nullable|string',
            'level'=>'nullable|in:1,2,3'
        ];
    }
}
