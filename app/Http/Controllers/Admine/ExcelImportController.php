<?php

namespace App\Http\Controllers\Admine;

use Illuminate\Http\Request;
use App\Imports\ParentImport;
use App\Imports\StudentImport;
use App\Imports\TeacherImport;
use App\Services\ExcelService;
use App\Http\Controllers\Controller;

class ExcelImportController extends Controller
{
    protected $excelService;

    public function __construct(ExcelService $excelService)
    {
        $this->excelService = $excelService;
    }

    public function importStudents(Request $request)
    {
        $file = $request->file('excel');
        
        $data= $this->excelService->import($file, new StudentImport());
        return  $data;
    }

    public function importTeachers(Request $request)
    {
        $file = $request->file('excel');
        $data= $this->excelService->import($file, new TeacherImport());
        return $data;
    }

    public function importParents(Request $request)
    {
        $file = $request->file('excel');
        $data=$this->excelService->import($file, new ParentImport());
        return $data;
    }
}
