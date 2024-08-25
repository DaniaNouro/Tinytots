<?php

namespace App\Http\Controllers\Admine;
use App\Models\Lesson;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;



class LessonController extends Controller
{
    public function createLesson(StoreLessonRequest $request){
        $lesson = Lesson::create($request->all());
        return $lesson;
    }

/*________________________________________________________________________*/
   
    public function showAllLessons()
    {
        $lessons = Lesson::with('teacher', 'subject', 'class')
                            ->get()
                            ->map(function ($lesson) {
                                $weekdayName = [
                                    '1' => 'Sunday',
                                    '2' => 'Monday',
                                    '3' => 'Tuesday',
                                    '4' => 'Wednesday',
                                    '5' => 'Thursday',
                                    '6' => 'Friday',
                                    '7' => 'Saturday',
                                ][$lesson->weekday];
    
                                return [
                                    'id' => $lesson->id,
                                    'teacher_name' => $lesson->teacher->first_name . ' ' . $lesson->teacher->last_name, // اسم المعلم
                                    'subject_name' => $lesson->subject->name,
                                    'class_name' => $lesson->class->class_name, 
                                    'weekday' => $weekdayName,
                                    'start_time' => $lesson->start_time,
                                    'end_time' => $lesson->end_time,
                                ];
                            });
    
        return response()->json($lessons);
    }
/*__________________________________________________________________*/
public function deleteLesson($lessonID)
{   
    $lesson=Lesson::findOrFail($lessonID);
    $lesson->delete();
    return response()->json(['lesson'=>$lesson,'message' => 'lesson deleted succefully']);
}
/*___________________________________________________________________*/
public function updateLesson(UpdateLessonRequest $request, $lessonId)
{
    $lesson = Lesson::findOrFail($lessonId);
   
    $lesson->update([
        'class_id' => $request->input('class_id') ?? $lesson->class_id,
        'teacher_id' => $request->input('teacher_id') ?? $lesson->teacher_id,
        'subject_id' => $request->input('subject_id') ?? $lesson->subject_id,
        'weekday' => $request->input('weekday') ?? $lesson->weekday,
        'start_time' => $request->input('start_time') ?? $lesson->start_time,
        'end_time' => $request->input('end_time') ?? $lesson->end_time,
    ]);

    return response()->json(['lesson'=>$lesson,'message'=>'Lesoon Updated successfully']); 
}
}
