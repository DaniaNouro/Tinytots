<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Studentcount_pointsRequest;
use App\Http\Requests\StudentEvaluationRequest;
use App\Http\Requests\StudentEvaluationsByDateRequest;
use App\Http\Requests\StudentEvaluationsInInLimitedPeriodRequest;
use App\Http\Requests\StudentEvaluationsInSpecificDayRequest;
use App\Http\Requests\StudentEvaluationsRequest;
use App\Http\Requests\StudentNeedworkPointRequest;
use App\Http\Requests\StudentPointsInLimitedPeriodRequest;
use App\Http\Requests\StudentPointsInSpecificDayRequest;
use App\Http\Requests\StudentPositivePointRequest;
use App\Http\Requests\StudentSortByAlphabetRequest;
use App\Http\Requests\StudentSortByPositivePointRequest;
use App\Http\Requests\StudentsRandomEvaluationRequest;
use App\Http\Requests\StudentUpdateNameAndValuePointRequest;
use App\Http\Requests\StudentUpdatePointRequest;
use App\Http\Requests\StudentUpdateValuePointRequest;
use App\Http\Requests\TeacherAllPointRequest;
use App\Http\Requests\TeacherUpdateNeedworkPointRequest;
use App\Http\Requests\TeacherUpdatePositivePointRequest;
use App\Http\Responces\Response;
use App\Services\TeacherEvaluationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class EvaluationController extends Controller
{
    protected $teacherEvaluationService;
    public function __construct(TeacherEvaluationService $teacherEvaluationService)
    {
        $this->teacherEvaluationService = $teacherEvaluationService;   
    }


    public function StudentEvaluation(StudentEvaluationRequest $request): JsonResponse {
        try {
            $validatedData = $request->validated();
            $result = $this->teacherEvaluationService->StudentEvaluation($validatedData);
            return Response::Success($result['data'], $result['message']);
           
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }

    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function RandomEvaluation($classID){
        $data=$this->teacherEvaluationService->RandomEvaluation($classID);
        return $data;
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function ReturnStudentEvaluations($studentId){
    $data=$this->teacherEvaluationService->ReturnStudentEvaluations($studentId);
        return $data;

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function ReturnStudentEvaluationInSpecificPeriod(StudentEvaluationsInInLimitedPeriodRequest $request): JsonResponse{
    try {
        $validatedData = $request->validated();
        $result = $this->teacherEvaluationService->ReturnStudentEvaluationInSpecificPeriod($validatedData);
        return Response::Success($result['data'], $result['message']);
       
    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message);
    }  
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function ReturnPointsOfStudent($studentId){
    $data=$this->teacherEvaluationService->ReturnPointsOfStudent($studentId);
    return $data;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function GivingStudentsPositivePoints(StudentPositivePointRequest $request): JsonResponse{
    try {
        $validatedData = $request->validated();
        $result = $this->teacherEvaluationService->GivingStudentsPositivePoints($validatedData);
        return Response::Success($result['data'], $result['message']);
       
    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message);
    } 

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function GivingStudentsNeedworkPoints(StudentNeedworkPointRequest $request): JsonResponse{
    try {
        $validatedData = $request->validated();
        $result = $this->teacherEvaluationService->GivingStudentsNeedworkPoints($validatedData);
        return Response::Success($result['data'], $result['message']);
       
    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message);
    } 
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function UpdatePositivePoint(TeacherUpdatePositivePointRequest $request): JsonResponse{
    try {
        $result = $this->teacherEvaluationService->UpdatePositivePoint($request);
        return Response::Success($result['data'], $result['message']);
       
    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message);
    }  
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function UpdateNeedworkPoint(TeacherUpdateNeedworkPointRequest $request): JsonResponse{
    try {
        $result = $this->teacherEvaluationService->UpdateNeedworkPoint($request);
        return Response::Success($result['data'], $result['message']);
       
    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message);
    }  
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function DelatePositivePoint($pointId){
    $data=$this->teacherEvaluationService->DelatePositivePoint($pointId);
    return $data;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function DelateNeedWorkPoint($pointId){
    $data=$this->teacherEvaluationService->DelateNeedWorkPoint($pointId);
    return $data;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function ReturnAllPoint(TeacherAllPointRequest $request){
    $data=$this->teacherEvaluationService->ReturnAllPoint($request);
    return $data; 
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function count_points(Studentcount_pointsRequest $request){
    $data=$this->teacherEvaluationService->count_points($request);
    return $data; 
}
}