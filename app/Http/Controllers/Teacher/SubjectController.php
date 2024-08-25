<?php

namespace App\Http\Controllers\Teacher;
use App\Services\SubjectService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    private $SubjectService;
    public function  __construct(SubjectService $SubjectService){
      $this->SubjectService=$SubjectService;
    }

    public function showAllSubjects(){
        $data=$this->SubjectService->showAllSubjectsForTeacher();
        return  $data;
}
}