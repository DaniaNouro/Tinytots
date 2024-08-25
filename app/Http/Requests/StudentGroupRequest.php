<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentGroupRequest extends FormRequest
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
            'group_name' => 'required|string|max:255',
          
            'class_id'=>'required|exists:class_rooms,id',
            'students' => 'required|array',
            'students.*.id' => 'required|exists:students,id',
            'task_id'=>'required|exists:tasks,id',
        ];
        
    }
    public function messages()
{
    return [
        'students.*.id.exists' => 'Check The Student Name',
    ];
}

}
