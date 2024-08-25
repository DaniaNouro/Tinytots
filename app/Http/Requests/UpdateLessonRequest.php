<?php

namespace App\Http\Requests;


use App\Rules\LessonTimeAvailabillityRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
  
    public function authorize()
    {
       
        return true; 
    }

  
    public function rules()
    {
        return [
            'class_id' => [
                'nullable',
                'integer',
                'exists:classes,id', 
            ],
            'teacher_id' => [
                'nullable',
                'integer',
                'exists:teachers,id', 
            ],
            'subject_id' => [
                'nullable',
                'integer',
                'exists:subjects,id', 
            ],
            'weekday' => [
                'nullable',
                'integer',
                'min:1',
                'max:7',
            ],
            'start_time' => [
                'nullable',
                new LessonTimeAvailabillityRule(), //  قاعدة  التحقق  من  توفر  ال  وقت  
                'date_format:' . config('panel.lesson_time_format'),
            ],
            'end_time' => [
                'nullable',
                'after:start_time', //  تأكد  من  أن  `end_time`  بعد  `start_time`
                'date_format:' . config('panel.lesson_time_format'),
            ],
        ];
    }
}
