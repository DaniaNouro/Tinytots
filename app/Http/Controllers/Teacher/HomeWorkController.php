<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentSortByAlphabetRequest;
use App\Http\Requests\TeacherHomeworkRequest;
use App\Http\Requests\TeacherUpdateHomeworkRequest;
use App\Http\Responces\Response;
use App\Services\HomeWorkService;
use Illuminate\Http\Request;
use Throwable;

class HomeWorkController extends Controller
{
    protected $HomeworkService;
    public function __construct(HomeWorkService $HomeworkService)
    {
        $this->HomeworkService = $HomeworkService;   
    }
    
    public function uploadHomework(TeacherHomeworkRequest $request){
        $data=$this->HomeworkService->uploadHomework($request);
        return $data;

    }
    public function updateHomework(TeacherUpdateHomeworkRequest $request ,$homeworkId){
    $data=$this->HomeworkService->updateHomework($request , $homeworkId);
    return $data;

    }
    public function deleteHomework($homeworkId){
        $data=$this->HomeworkService->deleteHomework($homeworkId);
        return $data;
    }
    public function returnAllHomeWork(){
        $data=$this->HomeworkService->returnAllHomeWork();
        return $data;
    }
    public function returnHomeworksOfclassroom($classID){
        $data=$this->HomeworkService->returnHomeworksOfclassroom($classID);
        return $data;
    }
    public function NumOfStudentHasUploadedHomework($homeworkID){
    $data=$this->HomeworkService->NumOfStudentHasUploadedHomework($homeworkID);
        return $data;
    }
    public function NumOfStudentsNotUploadedHomework($classID,$homeworkID)
{
    $data=$this->HomeworkService->NumOfStudentsNotUploadedHomework($classID,$homeworkID);
        return $data;
}
public function getStudentsWithHomework($homeworkID)
{
    $data=$this->HomeworkService->getStudentsWithHomework($homeworkID);
        return $data;
}

public function getStudentsThatNotUploadedHomework($classID,$homeworkID)
{
    $data=$this->HomeworkService->getStudentsThatNotUploadedHomework($classID,$homeworkID);
        return $data;
}

}