<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceRrecordForTheStudentRequest;
use App\Http\Requests\ShowStudentsRequest;
use App\Http\Requests\StudentAttendanceInInLimitedPeriodRequest;
use App\Http\Requests\StudentAttendanceInSpecificDayRequest;
use App\Http\Requests\StudentAttendanceRequest;
use App\Http\Requests\StudentsAttendanceByDateRequest;
use App\Http\Requests\StudentsAttendanceReques;
use App\Http\Requests\TeacherUpdateAttendanceRequest;
use App\Http\Responces\Response;
use App\Models\Classroom;
use App\Services\TeacherAttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AttendanceController extends Controller
{ 

    protected $teacherAttendanceService;
    public function __construct(TeacherAttendanceService $teacherAttendanceService)
    {
        $this->teacherAttendanceService = $teacherAttendanceService;   
    }

    // public function takeAttendance(StudentAttendanceRequest $request): JsonResponse {
    //     try {
    //         $validatedData = $request->validated();
    //         $result = $this->teacherAttendanceService->takeAttendance($validatedData);
            
    //         return Response::Success($result['data'], $result['message']);

           
    //     } catch (Throwable $th) {
    //         $message = $th->getMessage();
    //         return Response::Error([], $message);
    //     }
    // }
////////////////////////////////////////////////////////////////////////////////////////
public function updateAttendance(TeacherUpdateAttendanceRequest $request): JsonResponse {
    try {
        $validatedData = $request->validated();
        $result = $this->teacherAttendanceService->updateAttendance($validatedData);
        
        return Response::Success($result['data'], $result['message']);

       
    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message);
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function takeMultipleAttendance(StudentsAttendanceReques $request): JsonResponse {
    try {
        $validatedData = $request->validated();

        $result = $this->teacherAttendanceService->takeMultipleAttendance($validatedData);

            return Response::Success($result['data'], $result['message']);

    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message, 500);
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////
public function AttendanceOnASpecificDate(StudentAttendanceInSpecificDayRequest $request): JsonResponse {
    try {
        $validatedData = $request->validated();

        $result = $this->teacherAttendanceService->AttendanceOnASpecificDate($validatedData);
        return Response::Success($result['data'], $result['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message, 500);
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function showStudents(ShowStudentsRequest $request ){
    try {
        $validatedData = $request->validated();
        $result = $this->teacherAttendanceService->ShowStudents($validatedData);
        return Response::Success($result['data'], $result['message']);
        
    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message, 500);
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function AttendanceRecordForTheStudent(StudentAttendanceInInLimitedPeriodRequest $request){
    try {
        $validatedData = $request->validated();
        $result = $this->teacherAttendanceService->AttendanceRecordForTheStudent($validatedData);
        return Response::Success($result['data'], $result['message']);
        
    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message, 500);
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function getStudentsDataWithPoints($classroomId)
{
    $studentsData = $this->teacherAttendanceService->getStudentsDataWithPoints($classroomId);
    
    return response()->json($studentsData);
}
}