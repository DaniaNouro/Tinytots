<?php
namespace App\Services;

use App\Http\Responces\Response;
use Maatwebsite\Excel\Facades\Excel;

class ExcelService
{
    public function import($file,$importer)
    {
         Excel::import($importer, $file);
         return response('data inserted successfuly',200);
    }

    public function export($data, $exporter, $fileName)
    {
         Excel::download($exporter, $fileName);
         return response('data inserted successfuly',200);
    }
}