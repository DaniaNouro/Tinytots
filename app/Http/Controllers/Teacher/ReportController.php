<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherCreateReportRequest;
use App\Http\Responces\Response;
use App\Services\TeacherReportService;
use Illuminate\Http\Request;
use Throwable;

class ReportController extends Controller
{

    protected $teacherReportService;
    public function __construct(TeacherReportService $teacherReportService)
    {
        $this->teacherReportService = $teacherReportService;   
    }
    public function CreateReports(TeacherCreateReportRequest $request){
        try {
            $validatedData = $request->validated();
            $result = $this->teacherReportService->CreateReports($validatedData);
            return Response::Success($result['data'], $result['message']);
           
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }
}
