<?php

namespace App\Services\ParentService;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\Positive_Point;
use App\Models\Evaluation_Student;
use Illuminate\Support\Facades\Auth;
use App\Models\EvaluationNeedworkpoint;
use App\Models\EvaluationPositivepoint;
use Illuminate\Support\Facades\DB;

class EvaluationAndAttendanceService{

   
/*___________________________________________________________________________________________*/ 
public function returnStudentEvaluations($request, $studentId)
{
    $student = Student::find($studentId);
    if (!$student) {
        return response()->json([
            'message' => 'student not found',
        ], 422);
    }
    
    $date = $request->input('date'); 
    $studentEvaluation = Evaluation_Student::where('student_id', $studentId)->pluck('evaluation_id');

    // فلترة التقييمات بناءً على التاريخ إذا كان موجودًا
    $positivePointsQuery = EvaluationPositivepoint::whereIn('evaluation_id', $studentEvaluation)
        ->join('positive__points', 'positive__points.id', '=', 'evaluation_positivepoints.positivePoint_id')
        ->join('evaluations','evaluations.id', '=' ,'evaluation_positivepoints.evaluation_id')
        ->select('positive__points.id', 'positive__points.name', 'positive__points.value', 'evaluations.date');
    
    $needWorkPointsQuery = EvaluationNeedworkpoint::whereIn('evaluation_id', $studentEvaluation)
        ->join('need_works', 'need_works.id', '=', 'evaluation_needworkpoints.needworkPoint_id')
        ->join('evaluations','evaluations.id', '=' ,'evaluation_needworkpoints.evaluation_id')
        ->select('need_works.id', 'need_works.name', 'need_works.value', 'evaluation_needworkpoints.notes', 'evaluations.date');
    
    if ($date) {
        $positivePointsQuery->whereDate('evaluations.date', $date);
        $needWorkPointsQuery->whereDate('evaluations.date', $date);
    }

    $positivePoints = $positivePointsQuery->get();
    $needWorkPoints = $needWorkPointsQuery->get();

    return response()->json([
        'data' => [
            'positive_points' => $positivePoints,
            'need_work_points' => $needWorkPoints
        ],
        'message' => 'These are all the points the student received.'
    ]);

}

/*________________________________________________________________________________________________*/
public function AttendanceRecordForTheStudent($request, $studentId)
{
    if (Auth::user()->hasRole('parent')) {
        $student = Student::find($studentId);
        if ($student) {
            $date = $request->input('date'); // الحصول على التاريخ من الطلب إذا كان موجودًا
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // فلترة الحضور بناءً على التاريخ إذا كان موجودًا
            if ($date) {
                $studentRecord = Attendance::where('student_id', $student->id)
                    ->whereDate('date', $date)
                    ->get(['id', 'classroom_id', 'date', 'student_id', 'status', 'created_at']);

                if ($studentRecord->isEmpty()) {
                    return ['data' => [], 'message' => 'No attendance records found for the specified date'];
                }

                return ['data' => $studentRecord, 'message' => 'Student attendance record for the specified date'];
            } 
            // فلترة الحضور بين فترتين زمنيتين إذا كان موجودًا
            elseif ($startDate && $endDate) {
                $studentRecord = Attendance::where('student_id', $student->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->get(['id', 'classroom_id', 'date', 'student_id', 'status', 'created_at']);

                if ($studentRecord->isEmpty()) {
                    return ['data' => [], 'message' => 'No attendance records found for the specified period'];
                }

                return ['data' => $studentRecord, 'message' => 'Student attendance record for the specified period'];
            }
            // إذا لم يتم توفير التاريخ أو الفترات الزمنية، يعيد كل سجل الحضور
            else {
                $studentRecord = Attendance::where('student_id', $student->id)
                    ->get(['id', 'classroom_id', 'date', 'student_id', 'status', 'created_at']);

                if ($studentRecord->isEmpty()) {
                    return ['data' => [], 'message' => 'No attendance records found for this student'];
                }

                return ['data' => $studentRecord, 'message' => 'Complete student attendance record'];
            }
        } else {
            return ['data' => [], 'message' => 'No attendance records found for this student'];
        }
    } else {
        return ['data' => [], 'message' => 'You are not allowed to do this'];
    }
}
}
