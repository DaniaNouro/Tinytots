<?php

namespace App\Http\Controllers\Admine;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentEditInformation;
use Illuminate\Http\Request;
//use App\Http\Requests\EditInformation;
use App\Http\Requests\StudentSignupRequest;
use App\Http\Responces\Response;
use App\Models\Student;
use App\Models\User;
use App\Services\AdmineService;
use App\Services\AdmineStudentService;
use Illuminate\Http\JsonResponse;
use Throwable;
class AdmineStudentController extends Controller
{
    protected $admineStudentService;
    public function __construct(AdmineStudentService $admineStudentService)
        {
            $this->admineStudentService = $admineStudentService;
           
        }
  

        public function createAccountStudent(StudentSignupRequest  $request):JsonResponse{
            $data=[];
            try{
             $data =$this->admineStudentService->createAccountStudent($request->validated());
            return Response::Success($data['user'],$data['message']);
            }catch(Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);
            }
           
           }

         ////////////////////////////////edit Account teacher ////////////////////////////////////////
public function editAccountStudent(StudentEditInformation $request,$studentId){
    $data=[];
    try{
    $data =$this->admineStudentService->editAccountStudent($request->validated(),$studentId);
    return Response::Success($data['user'],$data['message']);
    }catch(Throwable $th){
    $message=$th->getMessage();
    return Response::Error($data,$message);
    }
   
   }
////////////////////////////////////Show information of student//////////////////////////////////////
public function showAccountStudent($id){
    $data=[];
    try{
    $data =$this->admineStudentService->showAccountStudent($id);
    return Response::Success($data['user'],$data['message']);
    }catch(Throwable $th){
    $message=$th->getMessage();
    return Response::Error($data,$message);
    }
   
   }

////////////////////////////////////////////////////////////////////////////////////////////////////
public function sendEmailContentCode($id){
    $data=[];
    try{
    $data =$this->admineStudentService->sendEmailContentCode($id);
    return Response::Success($data['user'],$data['message']);
    }catch(Throwable $th){
    $message=$th->getMessage();
    return Response::Error($data,$message);
    }
   
   }

///////////////////////////////////////////////////////////////////////////////////////////////////
public function showAllStudent()
{
    $students = User::role('student')
                ->with('student') // افتراضيًا، يجب أن تكون هذه العلاقة مع البروفايل الخاص بالمعلم موجودة
                ->get()
                ->map(function ($student) {
                    return [
                        'id'=>$student->student->id,
                        'first_name' => $student->student->first_name,
                        'last_name' => $student->student->last_name,
                        'image' => $student->image ?? null, // تأكد من اسم العمود المستخدم في جدول البروفايل
                        'email' => $student->email,
                    ];
                });

    return $students;
}
//////////////////////////////////////////////////////////////////////////////////////////////
public function showStudentDetailes() {
    $students = Student::with('parent')->get();

    $students = $students->map(function($student) {
        $levelMapping = [
            1 => 'Kg1',
            2 => 'Kg2',
            3 => 'Kg3',
        ];
        return [
            'id' => $student->id,
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'gender' => $student->gender,
            'date_of_birth' => $student->date_of_birth,
            'address' => $student->address,
            'details' => $student->details,
            'level' => isset($levelMapping[$student->level]) ? $levelMapping[$student->level] : $student->level,
            'father_name' => $student->parent ? $student->parent->father_first_name . ' ' . $student->parent->father_last_name : null,
            'mother_name' => $student->parent ? $student->parent->mother_first_name . ' ' . $student->parent->mother_last_name : null,
        ];
    });

    return response()->json($students);
}



}
