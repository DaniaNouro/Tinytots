<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Subject;
use App\Models\AgeGroup;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubjectService
{

  public function uploadSubject($request)
  {

    $name = $request['name'];
    $file = $request->file('file_path');
    $ageGroup_id =$request['ageGroup_id'];
    $cleanName = preg_replace('/[^A-Za-z0-9-]/', '', $name);
    $extension = $file->getClientOriginalExtension();
    $fileName = $cleanName . '.' . $extension;
    $path = $file->storeAs('', $fileName, 'tinytots_subject');
    $subject = Subject::query()->create(
      [
        'name' => $name,
        'file_path' => '/subjects/'.$path,
        'ageGroup_id' =>$ageGroup_id
      ]
    );

    return $subject;
  }
  ///////////////////////////////////////////////////////////////////////////

  public function updateSubject($request, $subjectId)
  {
    $subject = Subject::findOrFail($subjectId);
    $updateData = [];


    if ($request->has('name')) {
      $newName = $request['name'];
      $cleanName = preg_replace('/[^A-Za-z0-9-]/', '', $newName);
      $updateData['name'] = $newName;

      if (!$request->hasFile('file_path') && $subject->file_path) {
        $pathInfo = pathinfo($subject->file_path);
        $extension = $pathInfo['extension'];
        $newName = $cleanName;
        $newFilepath = '/subjects/'.$newName . '.' . $extension;
        Storage::disk('tinytots_subject')->move($subject->file_path, $newFilepath);
        $updateData['file_path'] = $newFilepath;
      }
    } else {
      $cleanName = preg_replace('/[^A-Za-z0-9-]/', '', $subject->name);
    }
    if ($request->hasFile('file_path')) {

      if ($subject->file_path) {
        Storage::disk('tinytots_subject')->delete($subject->file_path);
      }

      $file = $request->file('file_path');
      $extension = $file->getClientOriginalExtension();
      $fileName = $cleanName . '.' . $extension;
      $path = $file->storeAs('', $fileName, 'tinytots_subject');
      $updateData['file_path'] = $path;
    }
    $updateData['ageGroup_id']=$request['ageGroup_id'] ?? $subject->ageGroup_id;

    $subject->update($updateData);

    return $subject;
  }
  ///////////////////////////////////////////////////////////////////////////////////
  public function deleteSubject($subjectId)
  {
    $subject = Subject::findOrFail($subjectId);
    $path = $subject->file_path;
    Storage::disk('tinytots_subject')->delete($path);
    $subject->delete();
    return $subject;
  }
  //////////////////////////////////////////////////////////////////////////////////
  public function showSubject($subjectId)
  {
    $subject = Subject::findOrFail($subjectId);
    return $subject;
  }
  //////////////////////////////////////////////////////////////////////////////////

  public function showAllSubjects()
  { 
   $subjects= Subject::get();

   // Loop through each subject 
   foreach ($subjects as $subject) {
       // Check the ageGroup_id
       if ($subject->ageGroup_id == 1) {
           $subject->ageGroup_id = "Kg1"; 
       } elseif ($subject->ageGroup_id == 2) {
           $subject->ageGroup_id = "Kg2";
       } elseif ($subject->ageGroup_id == 3) {
           $subject->ageGroup_id = "Kg3";
       }
   }

   return $subjects;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  public function showAllSubjectsForParentAndStudent($studentId)
  { 
    $user = Auth::user()->id;

    // العثور على الطالب المناسب بناءً على studentId إذا تم تمريره، وإلا استخدم معرف المستخدم المصادق عليه
    if ($studentId != null) {
        $student = Student::find($studentId);
    } else {
        $student = Student::where('user_id', $user)->first();
    }

    // التأكد من العثور على الطالب
    if (!$student) {
        return response()->json(['error' => 'Student not found.'], 404);
    }

    // الحصول على مستوى الطالب
    $ageGroup_id = $student->level;
    $ageGroup = AgeGroup::find($ageGroup_id);

    // التأكد من العثور على مجموعة العمر
    if (!$ageGroup) {
        return response()->json(['error' => 'Age group not found.'], 404);
    }

    // الحصول على المواد الدراسية لمجموعة العمر
    $subjects = $ageGroup->subjects;

    return response()->json($subjects);
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  public function showAllSubjectsForTeacher()
  { 
    $user = Auth::user();
    $teacher = $user->teacher;
   
    $subjects = $teacher->lessons()
                      ->distinct('subject_id')
                      ->pluck('subject_id')
                      ->map(function ($subject_id) {
                        return Subject::where('id',$subject_id)->first(['id','name', 'file_path']);
                      });
  
    return  $subjects; 
}
}