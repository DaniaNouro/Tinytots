<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupNameUpdateRequest extends FormRequest
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
            'group_id' => 'required|exists:groups,id',
            'group_name' => 'nullable|string|max:100', 
            'task_id' => 'nullable|exists:tasks,id',
        ];
    }

}
