<?php

namespace App\Http\Controllers\Admine;

use Throwable;
use App\Models\User;
use App\Models\Parentt;
use Illuminate\Http\Request;
use App\Services\AdmineService;
use App\Http\Responces\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\AdmineParentService;
use App\Http\Requests\ParentSignupRequest;
use App\Http\Requests\ParentEditInformation;

class AdmineParentController extends Controller
{
    protected $admineParentService;
    public function __construct(AdmineParentService $admineParentService)
        {
            $this->admineParentService = $admineParentService;
           
        }
 public function createAccountParent(ParentSignupRequest  $request):JsonResponse{
 $data=[];
 try{
  $data =$this->admineParentService->createAccountParent($request->validated());
 return Response::Success($data['user'],$data['message']);
 }catch(Throwable $th){
 $message=$th->getMessage();
 return Response::Error($data,$message);
 }

}
/////////////////////Edit Account Parent///////////////////////////////
public function editAccountParent(ParentEditInformation $request,$parentId){
    $data=[];
    try{
    $data =$this->admineParentService->editAccountParent($request->validated(),$parentId);
    return Response::Success($data['user'],$data['message']);
    }catch(Throwable $th){
    $message=$th->getMessage();
    return Response::Error($data,$message);
    }
} 
/////////////////////////////////////////////////////////////////////////////////
public function showAccountParent($id){
    $data=[];
    try{
    $data =$this->admineParentService->showAccountParent($id);
    return Response::Success($data['user'],$data['message']);
    }catch(Throwable $th){
    $message=$th->getMessage();
    return Response::Error($data,$message);
    }
} 
//////////////////////////////////////////////////////////////////////
public function sendEmailContentInformationParent($id){
    $data=[];
    try{
    $data =$this->admineParentService->sendEmailContentInformationParent($id);
    return Response::Success($data['user'],$data['message']);
    }catch(Throwable $th){
    $message=$th->getMessage();
    return Response::Error($data,$message);
    }
   
   }
//////////////////////////////////////////////////////////////////////////////////
public function showAllParent()
{
    $parents = User::role('parent')
                ->with('parent') // افتراضيًا، يجب أن تكون هذه العلاقة مع البروفايل الخاص بالمعلم موجودة
                ->get()
                ->map(function ($parent) {
                    return [
                        'id'=>$parent->parent->id,
                        'first_name' => $parent->parent->father_first_name,
                        'last_name' => $parent->parent->father_last_name,
                        'image' => $parent->parent->image ?? null, // تأكد من اسم العمود المستخدم في جدول البروفايل
                        'email' => $parent->email,
                    ];
                });

    return $parents;
}
///////////////////////////////////////////////////////////////////////////////////
public function showParentsDetailes(){
    $users = Parentt::all();/*->paginate(1)*/;
    return $users->makeHidden('user_id');
  }  
}
