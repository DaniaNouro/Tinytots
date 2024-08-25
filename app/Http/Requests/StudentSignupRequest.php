<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentSignupRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email',
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'gender' => 'required|in:male,female,Male,Female',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'deatails' => 'nullable|string',
            'level' => 'required|in:1,2,3'
        ];
    }
}
