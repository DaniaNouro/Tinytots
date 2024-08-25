<?php

namespace App\Http\Controllers\Admine;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Attendance;
use App\Models\Report;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;

class ReportController extends Controller
{
    public function getTeachersCountReport()
    {
        $teachersCount = User::whereHas('roles', function($query) {
            $query->where('name', 'teacher');
        })->count();
    
        return response()->json([
            'report_type' => 'Teachers Count',
            'data' => [
                'teachers_count' => $teachersCount
            ],
            'report_date' => now()->toDateString()
        ]);
    }
/*______________________________________________________________________________*/

    public function getStudentsCountPerClassReport()
    {
        $classes =ClassRoom::withCount('students')->get();
    
        $reportData = $classes->map(function($class) {
            return [
                'class_name' => $class->class_name,
                'students_count' => $class->students_count
            ];
        });
    
        return response()->json([
            'report_type' => 'Students Count Per Class',
            'data' => $reportData,
            'report_date' => now()->toDateString()
        ]);
    }
    
 /*______________________________________________________________________________*/   

 public function getDailyAttendanceReport()
{
    $date = now()->toDateString();

    // Fetch attendance records for the current date
    $attendanceRecords = Attendance::whereDate('date', $date)->get();

    // Counting different statuses
    $statuses = ['present', 'absent', 'late', 'Left_early'];
    $statusCounts = [];
    foreach ($statuses as $status) {
        $statusCounts[$status] = $attendanceRecords->where('status', $status)->count();
    }

    // Calculate not taken count
    $totalClasses = ClassRoom::count();
    $classesWithRecords = $attendanceRecords->groupBy('classroom_id');
    $classAttendance = [];

    foreach ($classesWithRecords as $class_id => $records) {
        $classAttendance[$class_id] = [
            'class_name' => $records->first()->classroom->class_name, // Assuming Classroom model has 'name' attribute
            'present_count' => $records->where('status', 'present')->count(),
            'absent_count' => $records->where('status', 'absent')->count(),
            'late_count' => $records->where('status', 'late')->count(),
            'left_early_count' => $records->where('status', 'Left_early')->count(),
        ];
    }

    // Prepare JSON response
    $response = [
        'report_type' => 'Daily Attendance Report',
        'data' => [
            'present_count' => $statusCounts['present'],
            'absent_count' => $statusCounts['absent'],
            'late_count' => $statusCounts['late'],
            'left_early_count' => $statusCounts['Left_early'],
            'not_taken_count' => $totalClasses - count($classesWithRecords),
            'class_attendance' => $classAttendance,
        ],
        'report_date' => $date
    ];

    return response()->json($response);
}
/*______________________________________________________________________________*/




    
}

