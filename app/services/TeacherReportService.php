<?php

namespace App\Services;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Attendance;
use App\Models\NeedWork;
use App\Models\Positive_Point;
use App\Models\Report;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\TeacherAttendanceServiceAttendanceService;
class TeacherReportService{
    
  public function CreateReports($request) {
    if (Auth::user()->hasRole('teacher')) {
        $user = Auth::user()->id;
        $teacherId = Teacher::where('user_id', $user)->first()->id;

        $student = Student::find($request['student_id']);

        $attendanceService = new TeacherAttendanceService();
        $attendanceData = $attendanceService->AttendanceRecordForTheStudent($request);
        
        $pointService = new TeacherEvaluationService();
        $Points = $pointService->ReturnStudentEvaluationInSpecificPeriod($request);
        
        $SumPointService = new TeacherEvaluationService();
        $sumPointsData = $SumPointService->ReturnPointsOfStudent($student->id);

        $attendanceDataValue = is_array($attendanceData) ? ($attendanceData['data'] ?? []) : [];
        $pointsDataValue = is_array($Points) ? ($Points['data'] ?? []) : [];
        $totalPointsValue = is_array($sumPointsData) ? ($sumPointsData['data']['PointsOfStudent'] ?? 0) : 0;

        $reportData = Report::create([
            'teacher_id' => $teacherId,
            'student_id' => $student->id,
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'dateOfReport' => $request['dateOfReport'],
        ]);

        return [
            'data' => [
                'student' => $student->first_name . " " . $student->last_name,
                'attendance' => $attendanceDataValue,
                'points' => $pointsDataValue,
                'total_points' => $sumPointsData
            ],
            'message' => 'Student report generated successfully'
        ];
    } else {
        return ['data' => [], 'message' => "you are not allowed to do this"];
    }
}

}
