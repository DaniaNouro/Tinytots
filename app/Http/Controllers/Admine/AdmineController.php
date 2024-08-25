<?php

namespace App\Http\Controllers\Admine;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherEditInformation;
use App\Http\Requests\TeacherSignupRequest;
use App\Http\Responces\Response;
use App\Models\Teacher;
use App\Models\User;
use App\Services\AdmineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AdmineController extends Controller
{
    protected $admineService;
    public function __construct(AdmineService $admineService)
        {
            $this->admineService = $admineService;
           
        }
 public function createAccountTeacher(TeacherSignupRequest  $request):JsonResponse{
 $data=[];
 try{
  $data =$this->admineService->createAccountTeacher($request->validated());
 return Response::Success($data['user'],$data['message']);
 }catch(Throwable $th){
 $message=$th->getMessage();
 return Response::Error($data,$message);
 }

}
////////////////////////////////edit Account teacher ////////////////////////////////////////
public function editAccountTeacher(TeacherEditInformation $request,$teacherId){
    $data=[];
    try{
    $data =$this->admineService->editAccountTeacher($request->validated(),$teacherId);
    return Response::Success($data['user'],$data['message']);
    }catch(Throwable $th){
    $message=$th->getMessage();
    return Response::Error($data,$message);
    }
   
   }
////////////////////////////////////Show information of teacher//////////////////////////////////////
public function showAccountTeacher($id){
    $data=[];
    try{
    $data =$this->admineService->showAccountTeacher($id);
    return Response::Success($data['user'],$data['message']);
    }catch(Throwable $th){
    $message=$th->getMessage();
    return Response::Error($data,$message);
    }
   
   }
////////////////////////////////////////////////////////////////////////////////////////////////////
public function sendEmailContentInformationTeacher($id){
    $data=[];
    try{
    $data =$this->admineService->sendEmailContentInformationTeacher($id);
    return Response::Success($data['user'],$data['message']);
    }catch(Throwable $th){
    $message=$th->getMessage();
    return Response::Error($data,$message);
    }
   
   }
/////////////////////////////////////////////////////////////////////////////////////////////
public function showAllTeacher()
{
    $teachers = User::role('teacher')
                ->with('teacher') // افتراضيًا، يجب أن تكون هذه العلاقة مع البروفايل الخاص بالمعلم موجودة
                ->get()
                ->map(function ($teacher) {
                    return [
                        'id'=>$teacher->teacher->id,
                        'first_name' => $teacher->teacher->first_name,
                        'last_name' => $teacher->teacher->last_name,
                        'image' => $teacher->image ?? null, // تأكد من اسم العمود المستخدم في جدول البروفايل
                        'email' => $teacher->email,
                    ];
                });

    return $teachers;
}
/////////////////////////////////////////////////////////////////////////////////////////////
  public function showTeacherDetailes(){
    $users = Teacher::all();/*->paginate(1)*/;
    return $users->makeHidden('user_id');
  }




}
