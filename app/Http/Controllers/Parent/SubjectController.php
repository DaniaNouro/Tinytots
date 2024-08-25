<?php

namespace App\Http\Controllers\Parent;

use Illuminate\Http\Request;
use App\Services\SubjectService;
use App\Http\Controllers\Controller;


class SubjectController extends Controller
{
    private $SubjectService;
    public function  __construct(SubjectService $SubjectService){
      $this->SubjectService=$SubjectService;
    }

    public function showAllSubjects($studentId=null){
        $data=$this->SubjectService->showAllSubjectsForParentAndStudent($studentId);
        return  $data;
    }
}
