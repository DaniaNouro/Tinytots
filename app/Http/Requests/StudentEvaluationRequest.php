<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentEvaluationRequest extends FormRequest
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
            'date' => 'required|date',
            'type' => 'required|boolean',
            'student_id' => 'required|exists:students,id',
            'positivepoint_id' => 'nullable|exists:positive__points,id',
            'needwork_id' => 'nullable|exists:need_works,id',
            'notes' => 'nullable|string|max:300|min:10'
        ];
    }
}
