<?php

namespace App\Services;

use App\Http\Controllers\Teacher\HomeWorkController;
use App\Models\AgeGroup;
use App\Models\Classroom;
use App\Models\ClassRoomStudent;
use App\Models\home_work;
use App\Models\HomeWork;
use App\Models\HomeworkStudent;
use App\Models\HomeworkstudentRelationship;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeWorkService
{

  public function uploadHomework($request)
{
    $user = Auth::user()->id;
    $teacherId = Teacher::where('user_id', $user)->first()->id;
    $name = $request['homework_name'];
    $file = $request->file('homework_path');
    $classrooms = $request['classroom_ids'];
    $cleanName = preg_replace('/[^A-Za-z0-9-]/', '', $name);
    $extension = $file->getClientOriginalExtension();
    $fileName = $cleanName . '.' . $extension;
    $path = $file->storeAs('', $fileName, 'homeworks');
    $uploadedHomeworks = [];

    foreach ($classrooms as $classroom) {
      
        $homework = HomeWork::create(
            [
                'teacher_id' => $teacherId,  
                'homework_name' => $name,
                'homework_path' => "homeworks/" . $path,
                'classroom_id' => $classroom
            ]
        );
        $uploadedHomeworks[] = $homework;
    }

    return ['data' => $uploadedHomeworks, 'message' => 'The Homework Was Uploaded Successfully'];
}
  ///////////////////////////////////////////////////////////////////////////
  public function updateHomework($request, $homeworkId)
  {
      $homework = HomeWork::findOrFail($homeworkId);
      $updateData = [];
  
      //update homework name
      if ($request->has('homework_name')) {
          $newName = $request['homework_name'];
          $cleanName = preg_replace('/[^A-Za-z0-9-]/', '', $newName);
          $updateData['homework_name'] = $newName;
          //update homework path
          if (!$request->hasFile('homework_path') && $homework->homework_path) {
              $pathInfo = pathinfo($homework->homework_path);
              $extension = $pathInfo['extension'];
              $newName = $cleanName;
              $newhomeworkpath = $newName . '.' . $extension;
              Storage::disk('homeworks')->move($homework->homework_path, $newhomeworkpath);
              $updateData['homework_path'] = $newhomeworkpath;
          }
      } else {
          $cleanName = preg_replace('/[^A-Za-z0-9-]/', '', $homework->homework_name);
      }
      //upload new homework
      if ($request->hasFile('homework_path')) {
          if ($homework->homework_path) {
              Storage::disk('homeworks')->delete($homework->homework_path);
          }
          $file = $request->file('homework_path');
          $extension = $file->getClientOriginalExtension();
          $fileName = $cleanName . '.' . $extension;
          $path = $file->storeAs('', $fileName, 'homeworks');
          $updateData['homework_path'] = $path;
      }
  
      // تحديث  classroom_ids
      if ($request->has('classroom_ids')) {
          $classIds = $request['classroom_ids'];
          $homework->classroom_id = $classIds[0]; 
          $homework->save();
      }
  
      $homework->update($updateData);
      return $homework;
  }
  
//   ///////////////////////////////////////////////////////////////////////////////////
  public function deleteHomework($homeworkId)
  {
    $homework = HomeWork::findOrFail($homeworkId);
    $path = $homework->homework_path;
    Storage::disk('homeworks')->delete($path);
    $homework->delete();
    return ['data' => $homework, 'message' => 'The HomeWork Has Deleted Successfully'];
  }    
//   //////////////////////////////////////////////////////////////////////////////////
public function returnAllHomeWork(){
  $user = Auth::user()->id;
  $teacherId = Teacher::where('user_id', $user)->first()->id;

  $homeWorks=HomeWork::join('class_rooms', 'home_works.classroom_id', '=', 'class_rooms.id')
      ->where('home_works.teacher_id',$teacherId)
      ->select('class_rooms.class_name', 'home_works.classroom_id', DB::raw('count(*) as count'))
      ->groupBy('home_works.classroom_id', 'class_rooms.class_name')
      ->get();

  return ['data' => $homeWorks ,'message' => 'This Is All Homewok Was Uploaded'];
}

//////////////////////////////////////////////////////////////////////////////////////////
public function returnHomeworksOfclassroom($classID){
  $homeworks=HomeWork::where('classroom_id',$classID)->select('id','homework_name','homework_path')->get();

  return ['data' => $homeworks , 'message' => 'All Homework For This Classroom'];
}
/////////////////////////////////////////////////////////////////////////////////////////////////
public function NumOfStudentHasUploadedHomework($homeworkID)
{
    $homeworkStudent = HomeworkStudent::where('homework_id', $homeworkID)->pluck('id');

    $studentsWithHomework = HomeworkstudentRelationship::whereIn('homeworkstudent_id', $homeworkStudent)
        ->pluck('student_id');
        $numOfStudents=$studentsWithHomework->count();

    return [
        'data' => $numOfStudents,
        'message' => 'Number of students that uploaded homework',
    ];
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function NumOfStudentsNotUploadedHomework($classID, $homeworkID)
{
    $studentsInClass = ClassRoomStudent::where('class_room_id', $classID)->pluck('student_id');
    
    $homeworkStudent = HomeworkStudent::where('homework_id', $homeworkID)->pluck('id');

    $studentsWithHomework = HomeworkstudentRelationship::whereIn('homeworkstudent_id', $homeworkStudent)
        ->pluck('student_id');

    $studentsNotUploaded = $studentsInClass->diff($studentsWithHomework);

    $numOfStudents = $studentsNotUploaded->count();

    return [
        'data' => $numOfStudents,
        'message' => 'Number of students in the class who have not uploaded this homework',
    ];
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function getStudentsWithHomework($homeworkID)
{
    $studentsWithHomework = Homeworkstudent::where('homework_id', $homeworkID)
        ->join('homeworkstudent_relationships', 'homeworkstudent_relationships.id', '=', 'homework_students.id')
        ->join('students','students.id', '=' , 'homeworkstudent_relationships.student_id')
        ->select('students.id AS student_id',DB::raw('concat(students.first_name, \' \', students.last_name) AS student_name'),'homework_students.uploaded_homework AS homework_student')
        ->get();

    return [
        'data' => $studentsWithHomework,
        'message' => 'List of students that uploaded homework',
    ];
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function getStudentsThatNotUploadedHomework($classID, $homeworkID)
{
    $studentsInClass = ClassRoomStudent::where('class_room_id', $classID)->pluck('student_id');
    
    $homeworkStudent = HomeworkStudent::where('homework_id', $homeworkID)->pluck('id');
    
    $studentsWithHomework = HomeworkstudentRelationship::whereIn('homeworkstudent_id', $homeworkStudent)
        ->pluck('student_id');

    $studentsNotUploaded = $studentsInClass->diff($studentsWithHomework);

    $studentsDetails = Student::whereIn('id', $studentsNotUploaded)->select('id AS student_id', DB::raw('concat(first_name, \' \', last_name) AS student_name'))->get();

    return [
        'data' => $studentsDetails,
        'message' => 'List of students that did not upload homework',
    ];
}

 }