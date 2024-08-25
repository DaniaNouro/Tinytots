<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentHomeworkRequest;
use App\Services\Students\StudentHomeWorkService;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    protected $StudentHomeworkService;
    public function __construct(StudentHomeWorkService $StudentHomeworkService)
    {
        $this->StudentHomeworkService = $StudentHomeworkService;   
    }
    public function uploadHomeworkstudent(StudentHomeworkRequest $request,$homeworkId){
        $data=$this->StudentHomeworkService->uploadHomeworkstudent($request,$homeworkId);
        return $data;

    }
    public function updateHomeworkstudent(StudentHomeworkRequest $request ,$homeworkId){
        $data=$this->StudentHomeworkService->updateHomeworkstudent($request , $homeworkId);
        return $data;
    
        }

        public function deleteHomeworkStudent($homeworkId){
            $data=$this->StudentHomeworkService->deleteHomeworkStudent($homeworkId);
            return $data;
        }

        public function ReturnAllHomeworkToThisClass(){
            $data=$this->StudentHomeworkService->ReturnAllHomeworkToThisClass();
            return $data;
        }

        public function Returnhomeworkstudentthatuploaded(){
            $data=$this->StudentHomeworkService->Returnhomeworkstudentthatuploaded();
            return $data;
        }
}

