<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherUpdateHomeworkRequest extends FormRequest
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
            'homework_name' => 'nullable|string|max:255',
            'homework_path'=>'required|file|mimes:jpg,jpeg,png,pdf',
            'classroom_ids' => 'nullable|array',
            'classroom_ids.*' => 'nullable|integer|exists:class_rooms,id',
        ];
    }
}
