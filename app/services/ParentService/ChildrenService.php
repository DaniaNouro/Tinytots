<?php
namespace App\Services\ParentService;

use App\Models\Parentt;
use Illuminate\Support\Facades\Auth;

class ChildrenService{

public function getStudentByParentId(){

    $parentId=Auth::user()->id;
    $parent=Parentt::findOrFail($parentId);
    $students = $parent->students()->get(['id', 'first_name', 'last_name', 'level'])->map(function ($student) {
        return [
            'id' => $student->id,
            'full_name' => $student->first_name . ' ' . $student->last_name,
            'level' => ($student->level == 1 ? 'KG1' : 
            ($student->level == 2 ? 'KG2' :
            ($student->level == 3 ? 'KG3' :
             $student->level)))
        ];
    });
    return response()->json($students);
}

    public function getCountStudentsByParentId()
    {
        $parentId=Auth::user()->id;
        $parent=Parentt::findOrFail($parentId);
        $students =$parent->students()->count();
        return response()->json(['count_students'=>$students]);

    }
    
   
    }



