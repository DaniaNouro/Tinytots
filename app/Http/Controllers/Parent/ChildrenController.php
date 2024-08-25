<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Services\ParentService\ChildrenService;

use Illuminate\Http\Request;


class ChildrenController extends Controller
{
    private $ChildrenService;
    public function  __construct(ChildrenService $ChildrenService){
      $this->ChildrenService=$ChildrenService;
    }

  public function getStudentByParentId(){
    $data=$this->ChildrenService->getStudentByParentId();
    return $data;
  }

  public function getCountStudents(){
    $data=$this->ChildrenService->getCountStudentsByParentId();
    return $data;
  }


}
