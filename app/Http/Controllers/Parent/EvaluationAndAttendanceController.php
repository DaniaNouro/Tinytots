<?php

namespace App\Http\Controllers\Parent;

use App\Services\ParentService\EvaluationAndAttendanceService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvaluationAndAttendanceController extends Controller
{

    private $EvaluationAndAttendanceService;
    public function  __construct(EvaluationAndAttendanceService $EvaluationAndAttendanceService)
    {
        $this->EvaluationAndAttendanceService = $EvaluationAndAttendanceService;
    }

    public function returnEvaluationforStudent(Request $request, $studentId)
    {
        $data = $this->EvaluationAndAttendanceService->returnStudentEvaluations($request, $studentId);
        return $data;
    }


    public function returnAttendanceforStudent(Request $request, $studentId)
    {
        $data = $this->EvaluationAndAttendanceService->AttendanceRecordForTheStudent($request, $studentId);
        return $data;
    }
}
