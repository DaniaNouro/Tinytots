<?php

namespace App\Services;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\ClassRoomStudent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherAttendanceService{
  //take attendance student student
    public function takeAttendance($request){
      if (Auth::user()->hasRole('teacher')){
        $classroom = Classroom::find($request['classroom_id']);
        $date = $request['date'];
        $attendanceData = [];
        
              $studentIds = array_column($request['students'], 'id');
              $classroomStudents = $classroom->students()->whereIn('students.id', $studentIds)->pluck('students.id')->toArray();
      
              if (count($classroomStudents) != count($studentIds)) {
                  return [
                      'data' => [],
                      'message' => 'Some students are not in this classroom.'
                  ];
              }

        foreach ($request['students'] as $student) {
            $attendanceData[] = [
                'classroom_id' => $classroom ->id,
                'date' => $date,
                'student_id' => $student['id'],
                'status' => $student['status'],
               
            ];
        }
    
        Attendance::insert($attendanceData);
        $message = "Attendance has been recorded";
        return [  'data' => $attendanceData,'message' => $message];
      }
    else {
        return ['data' => [], 'message' => "you are not allowed to do this"];
      }
    }
//..................................................................................................
    public function updateAttendance($request){
      if (Auth::user()->hasRole('teacher')){
        $date=$request['date'];
        $studentIds=$request['students'];
        $newStatus=$request['status'];
        $updatedAttendance = [];

    foreach ($studentIds as $studentId) {
        $attendance = Attendance::where('student_id', $studentId)
                                 ->whereDate('date', $date)
                                 ->first();

        if ($attendance) { 
            $attendance->status = $newStatus;
            $attendance->save();
            $updatedAttendance[] = $attendance; 
        }
    }

  return [ 'data' => $updatedAttendance , 'message' => 'The Status has been successfully modified.'];

       } else {
          return ['data' => [], 'message' => "you are not allowed to do this"];
        }
    }
//..................................................................................................
//take attendance for group of student with the same status
public function takeMultipleAttendance($request)
{
    if (Auth::user()->hasRole('teacher')) {
        $classroom = Classroom::find($request['classroom_id']);
        $date = $request['date'];
        $status = $request['status'];

        $attendanceData = [];
        foreach ($request['studentIds'] as $studentId) {
            if (!Attendance::where('classroom_id', $classroom->id)
                ->where('date', $date)
                ->where('student_id', $studentId)
                ->exists()) {

                $attendanceData[] = [
                    'classroom_id' => $classroom->id,
                    'date' => $date,
                    'student_id' => $studentId,
                    'status' => $status,
                ];
            }
        }

        if (!empty($attendanceData)) {
            Attendance::insert($attendanceData);
            $message = "Attendance status '$status' has been recorded for multiple students";
            return ['data' => $attendanceData, 'message' => $message];
        } else {
            $message = "Attendance already exists for these students on this date";
            return ['data' => [], 'message' => $message];
        }

    } else {
        $message = "You are not allowed to do this";
        return ['data' => [], 'message' => $message];
    }
}

//....................................................................................................
//return attendance of student in the specific date
    public function AttendanceOnASpecificDate($request){
      if (Auth::user()->hasRole('teacher')){
        $classroomId=Classroom::find($request['classroom_id']);
        if($classroomId){
        $attendanceRecord=Attendance::where('classroom_id',$request['classroom_id'])
                                   -> where('date',$request['date'])
                                   ->get(); 
          if(!$attendanceRecord->isEmpty()){
            return [ 'data' => $attendanceRecord , 'message' => 'Attendance Record found'];
          }else{
            return ['data' => [],'message' => 'Attendance record not found for this date'];   
                 }
        }else{
          return ['data'=> [],'message' => 'Classroom not found'];}
        }
          else {
            return ['data' => [], 'message' => "you are not allowed to do this"];
          }
          }
        
//......................................................................................................................
public function showStudents($validatedData)
{
  if (Auth::user()->hasRole('teacher')) {
    $classroom = Classroom::find($validatedData['classroom_id']);

    if (!$classroom) {
        return ['data' => [], 'message' => 'Classroom not found'];
    }

    $students = $classroom->students()->with('user')->get();

    $studentsData = $students->map(function ($student) {
        return [
            'id' => $student->id,
            'name' => $student->first_name . ' ' . $student->last_name,
            'profile_picture' => $student->user->image,
        ];
    });

    return ['data' => $studentsData, 'message' => 'All students in this class'];
} else {
    return ['data' => [], 'message' => "You are not allowed to do this"];
}
}
//.........................................................................................................................
public function AttendanceRecordForTheStudent($request){
  if (Auth::user()->hasRole('teacher')){
    $student=Student::find($request['student_id']);
    if($student){
      $startDate=$request['start_date'];
      $endDate=$request['end_date'];
      $studentRecord=Attendance::where('student_id',$student->id)->whereBetween('date',[$startDate,$endDate])
      ->select('status', DB::raw('count(*) as count'))
      ->groupBy('status')
      ->pluck('count', 'status');


      return [ 'data' => $studentRecord ,'message' => 'Student attendance record'];
    }else{
      return [ 'data' => [] , 'message' => 'There is no student with this name'];
    }
  }
  else{
    return ['data' => [], 'message' => "you are not allowed to do this"];
  }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
private function calculateTotalPoints($studentId)
{
  $studentEvaluation = Evaluation_Student::where('student_id', $studentId)->pluck('evaluation_id');
    
  $positivePoints = EvaluationPositivepoint::whereIn('evaluation_id', $studentEvaluation)
      ->join('positive__points', 'positive__points.id', '=', 'evaluation_positivepoints.positivePoint_id')
      ->select('positive__points.value')
      ->sum('positive__points.value');
      
  $needWorkPoints = EvaluationNeedworkpoint::whereIn('evaluation_id', $studentEvaluation)
      ->join('need_works', 'need_works.id', '=', 'evaluation_needworkpoints.needworkPoint_id')
      ->select('need_works.value')
      ->sum('need_works.value');

  $totalPoints = $positivePoints + $needWorkPoints;

    return $totalPoints;
}

public function getStudentsDataWithPoints($classroomId)
{
    $classroom = Classroom::find($classroomId);

    if (!$classroom) {
        return ['data' => [], 'message' => 'Classroom not found'];
    }

    $students = $classroom->students()->with('user')->get();

    $studentsData = $students->map(function ($student) {
        $totalPoints = $this->calculateTotalPoints($student->id);

        return [
            'id' => $student->id,
            'name' => $student->first_name . ' ' . $student->last_name,
            'profile_picture' => $student->user->image,
            'total_points' => $totalPoints,
        ];
    });

    return ['data' => $studentsData, 'message' => 'Students data with total points in this class'];
}
 }

