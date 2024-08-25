<?php

namespace App\Http\Controllers\Student;

use Throwable;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Responces\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\StudentLoginService;
use App\Http\Requests\StudentLoginRequest;

class StudentController extends Controller

{  protected $studentLoginService;
    public function __construct(StudentLoginService $studentLoginService)
        {
            $this->studentLoginService =$studentLoginService;
           
        }


    public function login(StudentLoginRequest  $request){
        $data=[];
        try{
         $data =$this->studentLoginService->login($request);
        return Response::Success($data['user'],$data['message'],$data['code']);
        }catch(Throwable $th){
        $message=$th->getMessage();
        return Response::Error($data,$message);
        }
       
       }

       public function profileStudent(){
     
        $userId = Auth::user()->id;
        $user = Auth::user();

        $studentProfile = Student::where('user_id', $userId)->first();

        $levelMap = [
            1 => 'Kg1',
            2 => 'Kg2',
            3 => 'Kg3',
        ];

        $studentProfile->level = $levelMap[$studentProfile->level] ?? $studentProfile->level;

         // إزالة الحقول غير المرغوب فيها
         $studentData = $studentProfile->toArray();
         unset($studentData['user_id'],$studentData['parentt_id']);
      
        $profileData = [
            'image' => $user->image,
            'student_info' =>  $studentData
        ];

        return response()->json($profileData);


       }




   
}
