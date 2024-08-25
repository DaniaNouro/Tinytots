<?php
namespace App\Services;

use App\Models\AgeGroup;
use App\Models\ClassRoom;
use App\Models\Teacher;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

 class TeacherassignmentService{


  public function assignTeacherToClass($request){
    $rules = [
        'class_room_id' => 'required|exists:class_rooms,id',
        'teacher_id' => 'required|exists:teachers,id',
    ];
    $messages = [
        'class_room_id.required' => 'Please enter class and teacher',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
        $message = $validator->errors();
        return ['class' => '', 'message' => $message];
    }

    $classId = $request['class_room_id'];
    $teacherId = $request['teacher_id'];

    // Check if the teacher is already assigned to the class
    $class = ClassRoom::find($classId);
    if ($class->teachers()->where('teacher_id', $teacherId)->exists()) {
        $message = "The teacher is already assigned to this class.";
        return ['class' => '', 'message' => $message];
    }

    // Assign the teacher to the class if not already assigned
    $class->teachers()->syncWithoutDetaching($teacherId);
    $message = "Teacher assigned successfully";
    return ['class' => ['class_room_id' => $classId, 'teachers' => $this->getResponsibleTeachers($classId)], 'message' => $message];
}



/*_______________________________________________________________________________*/
public function getResponsibleTeachers($classId){
    $classRoom = ClassRoom::findOrFail($classId);
    $teachers = $classRoom->teachers()
        ->selectRaw('teachers.id as teacher_id, CONCAT(first_name, " ", last_name) as full_name')
        ->get()
        ->makeHidden('pivot');

    return [
        'class_room_id' => $classId,
        'teachers' => $teachers
    ];
}
/*_________________________________________________________________________________*/
 }

 
