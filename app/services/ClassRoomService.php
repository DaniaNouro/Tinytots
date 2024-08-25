<?php
namespace App\Services;

use Exception;
use App\Models\Teacher;
use App\Models\AgeGroup;
use App\Models\ClassRoom;
use App\Models\ClassRoomTeacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

 class ClassRoomService{

public function createClass($request,$id)
{
    $rules=[
        'class_name' => 'required|unique:class_rooms,class_name',
        'capacity' => 'required|numeric'
    ];
    $messages = [
        'class_name.unique' => 'Duplicated Class Name Please change it and try again',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
        $message=$validator->errors();
        return ['class'=>'','message'=>$message];
    }
   
    $class = ClassRoom::query()->create([
        'class_name' => $request->class_name,
        'capacity' => $request->capacity,
        'ageGroup_id' => $id
    ]);
    $message='class created successfuly';
    return ['class'=>$class,'message'=>$message];
}
/*_____________________________________________________________________________________________*/
public function editClass($request,$classId)
{
   
    $class = ClassRoom::findOrFail($classId);

    $rules = [
        'class_name' => 'unique:class_rooms,class_name,' . $class->id,
        'capacity' => 'numeric'
    ];

    $messages = [
        'class_name.unique' => 'Duplicated Class Name. Please change it and try again.',
        'capacity.numeric' => 'Capacity must be a number.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        $message = $validator->errors();
        return ['class' => '', 'message' => $message];
    }

    $newCapacity = $request->capacity;

    // التحقق من أن السعة الجديدة أكبر أو تساوي عدد الطلاب الحاليين
    if ($newCapacity !== null && $newCapacity < $class->students()->count()) {
        return ['class' => '', 'message' => 'The new capacity cannot be less than the current number of students in the class.'];
    }

    // تحديث بيانات الصف فقط إذا كانت السعة الجديدة صحيحة
    $class->class_name = $request->class_name ?? $class->class_name;
    if ($newCapacity !== null) {
        $class->capacity = $newCapacity;
    }
    $class->save();

    $message = 'Class updated successfully';
    return ['class' => $class, 'message' => $message];
}

// public function editClass($request)
// {
//     $class=ClassRoom::findOrFail($request['id']);
//     $rules=[
//         'class_name' => 'unique:class_rooms,class_name,'.$class->id,
//         'capacity' => 'numeric'
//     ];
//     $messages = [
//         'class_name.unique' => 'Duplicated Class Name Please change it and try again',
//     ];
//     $validator = Validator::make($request->all(), $rules, $messages);
//     if ($validator->fails()) {
//         $message=$validator->errors();
//         return ['class'=>'','message'=>$message];
//     }
//     $class ->update([
//         'class_name'=>$request->class_name??$class->class_name,
//         'capacity'=>$request->capacity??$class->capacity,
//     ]);
//     $message='class updated successfuly';
//     return ['class'=>$class,'message'=>$message];
// }
/*_____________________________________________________________________________________________*/
public function showClass($id)
{
$age=AgeGroup::findORFail($id);
$classes=$age->classRooms()->get();
$message='Showed Successfuly';
return ['classes'=>$classes,'message'=>$message];
}
/*_____________________________________________________________________________________________*/
public function showDetailesClass($classId)
{

    try {
       
       // $age = AgeGroup::findOrFail($ageGroupId);

      
        $class =classRoom::findOrFail($classId);

      
        $students = $class->students()->select('students.id', 'first_name', 'last_name')->get();

      
        $message = 'Data retrieved successfully';

     
        return [
            'class' => [
                'id' => $class->id,
                'class_name' => $class->class_name,
               
            ],
            'students' => $students,
            'message' => $message,
        ];
    } catch (Exception $e) {
        // إذا فشل البحث عن مجموعة الأعمار أو الصف المحدد، يمكنك إدارة الخطأ هنا
        return [
            'error' => 'Failed to retrieve class details. ' . $e->getMessage(),
        ];
    }
}






// public function showClass($id)
// {
//     $ageGroup = AgeGroup::findOrFail($id);

//     $classes = $ageGroup->classRooms()->get(['id', 'class_name']);

//     $formattedClasses = $classes->map(function ($class) {
//         return [
//             'class_id' => $class->id,
//             'class_name' => $class->class_name,
//         ];
//     });

//     $message = 'Showed Successfully';

//     return ['classes' => $formattedClasses, 'message' => $message];
// }


/*_____________________________________________________________________________________________*/
public function showClassbyTeacher($id)
{
$teacher=Teacher::findORFail($id);
$classes=$teacher->class_rooms()->get();
$message='Showed Successfuly';
return ['classes'=>$classes,'message'=>$message];
}
/*_____________________________________________________________________________________________*/
// public function showClassForTeacher($ageGroup)
// {
//     $user = Auth::user();
//     $teacherId = $user->teacher->id;

//     $classRoomsForTeacher = ClassRoomTeacher::where('teacher_id', $teacherId)->pluck('class_room_id');

//     $classes = ClassRoom::whereIn('id', $classRoomsForTeacher)
//                 ->where('ageGroup_id', $ageGroup)
//                 ->pluck('id','class_name');

//     return response()->json($classes, 200);
// }
public function showClassForTeacher($ageGroup)
{
    $user = Auth::user();
    $teacherId = $user->teacher->id;

    $classRoomsForTeacher = ClassRoomTeacher::where('teacher_id', $teacherId)->pluck('class_room_id');

    $classes = ClassRoom::whereIn('id', $classRoomsForTeacher)
                ->where('ageGroup_id', $ageGroup)
                ->get(['id', 'class_name']);

    $formattedClasses = $classes->map(function ($class) {
        return [
            'class_id' => $class->id,
            'class_name' => $class->class_name,
        ];
    });

    return response()->json(['classes' => $formattedClasses], 200);
}

/*_______________________________________________________________________________*/

public function deleteClass($classId){

    $class=ClassRoom::find($classId);
    if(!$class){
    return response()->json(['message'=>'class Not found'],404);
    }
    else{
    $class->delete();
    return response()->json(['class'=>$class,'message'=>'class deleted successfully'],200);
}
}


}