<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Students\StudentAttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected $studentAttendanceService;
    public function __construct(StudentAttendanceService $studentAttendanceService)
    {
        $this->studentAttendanceService = $studentAttendanceService;   
    }

public function ReturnAttendanceOfStudent(){
    $data=$this->studentAttendanceService->ReturnAttendanceOfStudent();
    return $data;
}
}
