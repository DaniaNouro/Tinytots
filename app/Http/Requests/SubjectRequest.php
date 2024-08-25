<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'=>'required|string',
            'file_path' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'ageGroup_id'=>'required|integer|in:1,2,3'
        ];
    }
}
