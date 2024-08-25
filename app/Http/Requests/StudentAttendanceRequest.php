<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentAttendanceRequest extends FormRequest
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
            'classroom_id' => 'required|exists:class_rooms,id',
            'date' => 'required|date',
            'students' => 'required|array',
            'students.*.id' => 'required|exists:students,id',
            
            'students.*.status' => 'required|in:present,absent,late,excused',
                ];
    }
}
 