<?php

namespace App\Http\Controllers\Teacher;

use App\Services\ClassRoomService;
use App\Http\Controllers\Controller;
use App\Http\Responces\Response;

class ClassRoomController extends Controller
{
    private $ClassRoomService;
    public function  __construct(ClassRoomService $ClassRoomService){
      $this->ClassRoomService=$ClassRoomService;
    }
    public function showClassForTeacher($ageGroupId){
        $data=$data=$this->ClassRoomService->showClassForTeacher($ageGroupId);
        return $data;
       }
}
