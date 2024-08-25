<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentEvaluationsInSpecificDayRequest;
use App\Services\Students\StudentEvaluationService;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    protected $studentEvaluationService;
    public function __construct(StudentEvaluationService $studentEvaluationService)
    {
        $this->studentEvaluationService = $studentEvaluationService;   
    }

    public function EvaluationStudentInSpecificDate(StudentEvaluationsInSpecificDayRequest $request){
        $data=$this->studentEvaluationService->EvaluationStudentInSpecificDate($request);
        return $data;
    }
//////////////////////////////////////////////////////////////////////////////////////////
    public function ReturnDateEvaluation(){
        $data=$this->studentEvaluationService->ReturnDateEvaluation();
        return $data;
    }
/////////////////////////////////////////////////////////////////////////////////////////////
public function SumPointOfStudent(){
    $data=$this->studentEvaluationService->SumPointOfStudent();
    return $data;
}
}
