<?php

namespace App\Http\Controllers\Admine;

use App\Http\Controllers\Controller;
use App\Models\AgeGroup;
use Illuminate\Http\Request;

class AgeGroupController extends Controller
{
    public function getAgeGroups(){

       $ageGroup=AgeGroup::get();
        return $ageGroup; 
    }
}
