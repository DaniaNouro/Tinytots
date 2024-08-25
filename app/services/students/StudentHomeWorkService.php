<?php

namespace App\Services\Students;

use App\Models\home_work;

use App\Models\HomeworkStudent;
use App\Models\Student;
use App\Models\ClassRoomStudent;
use App\Models\HomeWork;
use App\Models\HomeworkstudentRelationship;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentHomeWorkService
{

  public function uploadHomeworkstudent($request, $homeworkId)
  {
      $user = Auth::user()->id;
      $studentId = Student::where('user_id', $user)->first()->id;
  
      $previousHomework = HomeworkStudent::join('homeworkstudent_relationships', 'homework_students.id', '=', 'homeworkstudent_relationships.homeworkstudent_id')
          ->where('homework_students.homework_id', $homeworkId)
          ->where('homeworkstudent_relationships.student_id', $studentId)
          ->first();
  
      if ($previousHomework) {
          return [
              'data' => null,
              'message' => 'You have already uploaded a homework for this assignment',
          ];
      }
  
      $file = $request->file('uploaded_homework');
      $path = Storage::disk('homeworkstudent')->putFileAs('', $file, $file->getClientOriginalName());
      $publicPath = Storage::disk('homeworkstudent')->url($path);
  
      $homeworkstudent = HomeworkStudent::create([
          'homework_id' => $homeworkId,
          'uploaded_homework' => "homeworkstudent/" . $path
      ]);
  
      $homeworkstudentID = $homeworkstudent->id;
  
      $homeworkstudentRelation = HomeworkstudentRelationship::create([
          'student_id' => $studentId,
          'homeworkstudent_id' => $homeworkstudentID
      ]);
  
      return [
          'data' => $homeworkstudent,
          'message' => 'The Homework Was Uploaded Successfully',
      ];
  }
  ///////////////////////////////////////////////////////////////////////////
  public function updateHomeworkstudent($request, $homeworkId)
  {
      $homework = HomeworkStudent::findOrFail($homeworkId);
      $homeworkID=$homework->id;
      $updateData = [];
  
      //upload new homework
      if ($request->hasFile('uploaded_homework')) {
          if ($homework->uploaded_homework) {
              Storage::disk('homeworkstudent')->delete($homework->uploaded_homework);
          }
          $file = $request->file('uploaded_homework');
          $path = Storage::disk('homeworkstudent')->putFileAs('', $file, $file->getClientOriginalName());
          $updateData['uploaded_homework'] ="homeworkstudent/". $path;
      }
  
      $homework->update($updateData);
      return ['data' => [
        'homework_id' =>$homeworkID,
        'new_homework' =>"homeworkstudent/".$path
      ],'message' => 'Homework Has Updated Successfully'];
  }
  
//   ///////////////////////////////////////////////////////////////////////////////////
  public function deleteHomeworkStudent($homeworkId)
  {
    $homework = HomeworkStudent::findOrFail($homeworkId);
    $path = $homework->uploaded_homework;
    Storage::disk('homeworkstudent')->delete($path);
    $homework->delete();
    return ['data' => $homework, 'message' => 'The HomeWork Has Deleted Successfully'];
  }    
////////////////////////////////////////////////////////////////////////////////////
public function ReturnAllHomeworkToThisClass(){
   $user = Auth::user()->id;
   $studentId = Student::where('user_id', $user)->first()->id;
   $classID=ClassRoomStudent::where('student_id',$studentId)->first()->class_room_id;
   $homeworks=home_work::where('classroom_id',$classID)->pluck('homework_path');

  return [ 'data' => $homeworks , 'message' => 'This Is All Homework'];

}
//////////////////////////////////////////////////////////////////////////////////////////////////////
public function Returnhomeworkstudentthatuploaded()
{
  $user = Auth::user()->id;
  $studentId = Student::where('user_id', $user)->first()->id;
  $homeworkStudentIds = HomeworkstudentRelationship::where('student_id', $studentId)->pluck('homeworkstudent_id');
  $homeworks = HomeworkStudent::whereIn('id', $homeworkStudentIds)->pluck('uploaded_homework');

  return ['data' => $homeworks, 'message' => 'All Homeworks That Were Uploaded'];
}
}