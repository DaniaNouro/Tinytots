<?php

namespace App\Services\Students;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceService{

public function ReturnAttendanceOfStudent(){
     $user = Auth::user()->id;
     $studentId = Student::where('user_id', $user)->first()->id;

     $attendances=Attendance::where('student_id',$studentId)->select('date', 'status')
     ->get();

     return [ 'data' => $attendances , 'message' => 'All Attendance Of Student'];
}




}