<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    
    public function rules()
    {
        return [
            'name'=>'nullable|string',
            'file_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'ageGroup_id'=>'nullable|integer|in:1,2,3'
        ];
    }
}
