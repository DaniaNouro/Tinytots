<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherUpdatePost extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    
    public function rules()
    {
        return [
            'title' => 'nullabel|string|max:255',
            'content' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480',
            'visibility' => 'nullable|in:class,everyone',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:class_rooms,id',
        ];
    }
}
