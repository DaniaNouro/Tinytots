<?php

namespace App\Http\Controllers\Admine;

use App\Http\Controllers\Controller;
use App\Http\Responces\Response;
use App\Services\TeacherassignmentService;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{
   
    private $TeacherAssignmentService;
    public function  __construct(TeacherassignmentService $TeacherAssignService){
      $this->TeacherAssignmentService=$TeacherAssignService;
    }
/*________________________________________________________________________________*/
    public function assignTeacherToClass(Request $request){
     $data=$this->TeacherAssignmentService->assignTeacherToClass($request);
     if(!$data['class']) return Response::Validation('',$data['message']);
     else return Response:: Success($data['class'],$data['message']);
    }
/*_________________________________________________________________________________*/
public function getResponsibleTeachers($classId){
    $data=$this->TeacherAssignmentService->getResponsibleTeachers($classId);
    return $data;
}
/*_________________________________________________________________________________*/
}
