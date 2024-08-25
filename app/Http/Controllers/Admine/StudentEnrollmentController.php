<?php

namespace App\Http\Controllers\Admine;

use App\Http\Controllers\Controller;
use App\Http\Responces\Response;
use Illuminate\Http\Request;
use App\Services\StudentEnrollmentService;

class StudentEnrollmentController extends Controller
{
    private $StudentEnrollmentService;
    public function  __construct(StudentEnrollmentService $StudentEnrollmentService){
      $this->StudentEnrollmentService=$StudentEnrollmentService;
    }
/*________________________________________________________________________________*/
public function addStudentToClass(Request $request)
{
   $result=$this->StudentEnrollmentService->addStudentToClass($request);
  if((!empty($result['not_found']))||(!empty($result['students_existing']))||(empty($result['students_added']))){
    return Response::Error($result);
   }else{
    return Response::Success($result);
   }
}
/*________________________________________________________________________________*/
}