<?php

namespace App\Http\Controllers\Admine;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Responces\Response;
use App\Services\SubjectService;
use Illuminate\Http\Request;
use Throwable;

class SubjectController extends Controller
{
    private $SubjectService;
    public function  __construct(SubjectService $SubjectService){
      $this->SubjectService=$SubjectService;
    }

    public function uploadSubject(SubjectRequest $request){
        // try{
            $data =$this->SubjectService->uploadSubject($request);
           return $data;
        //    }catch(Throwable $th){
        //    $message=$th->getMessage();
        //    return Response::Error($data,$message);
        //    }

    }


    public function updateSubject(UpdateSubjectRequest $request,$subjectId){
        $data =$this->SubjectService->updateSubject($request,$subjectId);
        return $data;

    }

    public function deleteSubject($subjectId){
        $data =$this->SubjectService->deleteSubject($subjectId);
        return $data;

    }

    public function showAllSubjects(){
        $data=$this->SubjectService->showAllSubjects();
        return  $data;
    }

}
