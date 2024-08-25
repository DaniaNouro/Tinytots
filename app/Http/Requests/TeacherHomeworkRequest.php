<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherHomeworkRequest extends FormRequest
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
           'homework_name'=>'required|string',
           'homework_path'=>'required|file|mimes:jpg,jpeg,png,pdf',
           'classroom_ids' => 'required|array',
           'classroom_ids.*' => 'integer|exists:class_rooms,id',     
           ];
    }
    public function messages()
    {
        return [
            'classroom_ids.*.exists' => 'Check The Classroom Name',
        ];
    }
}
