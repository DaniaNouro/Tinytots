<?php

namespace App\Http\Requests;

use App\Rules\LessonTimeAvailabillityRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
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

  
    public function rules()
    {
        return [
           
                'class_id'   => [
                    'required',
                    'integer'],
                'teacher_id' => [
                    'required',
                    'integer',
                ],
                'subject_id' => [
                    'required',
                    'integer'],
                'weekday'    => [
                    'required',
                    'integer',
                    'min:1',
                    'max:7'],
                'start_time' => [
                    'required',
                    new LessonTimeAvailabillityRule(),
                    'date_format:' . config('panel.lesson_time_format')],
                'end_time'   => [
                    'required',
                    'after:start_time',
                    'date_format:' . config('panel.lesson_time_format')],
           
        ];
    }
}
