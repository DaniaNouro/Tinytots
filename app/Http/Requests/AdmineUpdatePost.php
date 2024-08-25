<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmineUpdatePost extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480',
            'visibility' => 'nullable|in:teachers,everyone',
        ];
    }
}
