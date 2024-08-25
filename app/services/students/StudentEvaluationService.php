<?php

namespace App\Services\Students;

use App\Models\Evaluation;
use App\Models\Evaluation_Student;
use App\Models\EvaluationNeedworkpoint;
use App\Models\EvaluationPositivepoint;
use App\Models\Positive_Point;
use App\Models\Student;
use App\Services\TeacherEvaluationService;
use Illuminate\Support\Facades\Auth;

// Service Class
class StudentEvaluationService{

        public function EvaluationStudentInSpecificDate($request)
        {
            $user = Auth::user()->id;
            $studentID = Student::where('user_id', $user)->first()->id;
            $specificDate = $request['date'];
        
            $studentEvaluation = Evaluation_Student::where('student_id', $studentID)->pluck('evaluation_id');
            $evaluationIDs = Evaluation::whereIn('id', $studentEvaluation)
                ->whereDate('date', $specificDate)
                ->pluck('id');
        
            $positivePoints = EvaluationPositivepoint::whereIn('evaluation_id', $evaluationIDs)
                ->join('positive__points', 'positive__points.id', '=', 'evaluation_positivepoints.positivePoint_id')
                ->join('evaluations', 'evaluations.id', '=', 'evaluation_positivepoints.evaluation_id')
                ->select('positive__points.id', 'positive__points.name', 'positive__points.value')
                ->get();
        
            $needWorkPoints = EvaluationNeedworkpoint::whereIn('evaluation_id', $evaluationIDs)
                ->join('need_works', 'need_works.id', '=', 'evaluation_needworkpoints.needworkPoint_id')
                ->join('evaluations', 'evaluations.id', '=', 'evaluation_needworkpoints.evaluation_id')
                ->select('need_works.id', 'need_works.name', 'need_works.value', 'evaluation_needworkpoints.notes')
                ->get();
        
            return [
                'data' => [
                    'positive_points' => $positivePoints,
                    'need_work_points' => $needWorkPoints
                ],
                'message' => 'These are all the points the student received on the specified date'
            ];
        }
        
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function ReturnDateEvaluation(){
    $user = Auth::user()->id;
    $studentId = Student::where('user_id', $user)->first()->id;
    $evaluationIds=Evaluation_Student::where('student_id',$studentId)->pluck('evaluation_id');
    $evaluatios=Evaluation::whereIn('id',$evaluationIds)->get('date');
    $uniqueDates = $evaluatios->unique('date');
        return [ 'date' =>$uniqueDates,'message' =>'These are all the dates on which the student was evaluated' ];
     }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function SumPointOfStudent(){
    $user = Auth::user()->id;
    $studentId = Student::where('user_id', $user)->first()->id;
    $SumPointService = new TeacherEvaluationService();
    $sumPointsData = $SumPointService->ReturnPointsOfStudent($studentId);
    return $sumPointsData;
}
//////////////////////////////////////////////////////////////////////////////////////////////////
}