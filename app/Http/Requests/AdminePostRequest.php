<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminePostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480',
            'visibility' => 'required|in:teachers,everyone',
        ];
    }
}
