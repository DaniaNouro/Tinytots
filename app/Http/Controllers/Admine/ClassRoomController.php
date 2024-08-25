<?php

namespace App\Http\Controllers\Admine;


use Illuminate\Http\Request;
use App\Http\Responces\Response;
use App\Services\ClassRoomService;
use App\Http\Controllers\Controller;



class ClassRoomController extends Controller
{ 
    private $ClassRoomService;
    public function  __construct(ClassRoomService $ClassRoomService){
      $this->ClassRoomService=$ClassRoomService;
    }
 /*______________________________________________________________________________*/   
    public function createClass(Request $request,$id)
    {
    $data=$this->ClassRoomService->createClass($request,$id);
    if(!$data['class']) return Response::Validation('',$data['message']);
    else return Response:: Success($data['class'],$data['message']);
    }
/*______________________________________________________________________________*/   
public function editClass(Request $request,$classId)
{
    $data=$this->ClassRoomService->editClass($request,$classId);
    if(!$data['class']) return Response::Validation('',$data['message']);
    else return Response:: Success($data['class'],$data['message']);
}
/*______________________________________________________________________________*/  

public function showClass($id){
 $data=$data=$this->ClassRoomService->showClass($id);
 return Response:: Success($data['classes'],$data['message']);
}
/*______________________________________________________________________________*/

public function showClassbyTeacher($id){
  $data=$data=$this->ClassRoomService->showClassbyTeacher($id);
  return Response:: Success($data['classes'],$data['message']);
 }
 /*________________________________________________________________________________*/
 public function showDetailesClass($classId){
  // استدعاء الدالة لاسترداد البيانات
  $data = $this->ClassRoomService->showDetailesClass( $classId);
  return $data;
  // التأكد من وجود خطأ قبل إعادة الاستجابة
//   if (isset($data['error'])) {
//       return Response::error($data['error']); // ترجمتها كان يعرض
//   }

//   // إعادة الاستجابة بنجاح
//   return Response::success([
//       'class' => $data['class'],
//       'students' => $data['students'],
//   ], $data['message']);
//  }
}

public function deleteClass($classId){

  $data = $this->ClassRoomService->deleteClass($classId);
  return $data;

}


}