<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentEvaluationsRequest;
use App\Http\Requests\StudentSortByAlphabetRequest;
use App\Http\Responces\Response;
use App\Services\TeacherSortService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class SortController extends Controller
{
    protected $teacherSortService;
    public function __construct(TeacherSortService $teacherSortService)
    {
        $this->teacherSortService = $teacherSortService;   
    }

    public function SortByAlphabet($classroom){
        $data=$this->teacherSortService->SortByAlphabet($classroom);
        return $data;
    }
//.......................................................................................................................
    public function SortByHighestPoints($classroom){
        $data=$this->teacherSortService->SortByHighestPoints($classroom);
        return $data;
    }
//............................................................................................................................
    public function SortByLowestPoints($classroom){
        $data=$this->teacherSortService->SortByLowestPoints($classroom);
        return $data;
    }

}
