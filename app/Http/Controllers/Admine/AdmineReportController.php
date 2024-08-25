<?php

namespace App\Http\Controllers\Admine;

use App\Http\Controllers\Controller;
use App\Models\AgeGroup;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdmineReportController extends Controller
{
    public function generateReport(Request $request)
    {
        $date = $request->input('date', Carbon::today());

        $teacherCount = Teacher::count();

        
        $totalStudentCount = Student::count();

       
        // $categories = AgeGroup::with('classRooms.students')->get()->map(function($category) use ($date) {
        //     $category->classRooms = $category->classRooms->map(function($classRoom) use ($date) {
        //         $presentCount = $classRoom->students()->whereHas('attendances', function($query) use ($date) {
        //             $query->whereDate('created_at', $date)->where('status', 'present');
        //         })->count();

        //         $absentCount = $classRoom->students()->whereHas('attendances', function($query) use ($date) {
        //             $query->whereDate('created_at', $date)->where('status', 'absent');
        //         })->count();

        //         $classRoom->present_count = $presentCount;
        //         $classRoom->absent_count = $absentCount;
        //         return $classRoom;
        //     });
        //     return $category;
        // });

        return response()->json([
            'teacher_count' => $teacherCount,
            'total_student_count' => $totalStudentCount,
            'categories' => $categories,
        ]);
    }
}
