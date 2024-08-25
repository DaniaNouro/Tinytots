<?php

namespace App\Http\Controllers\Parent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Parentt;
use App\Services\ParentService\ParentProfileService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
   
    private $ProfileService;
    public function  __construct(ParentProfileService $ProfileService){
      $this->ProfileService=$ProfileService;
    }


    public function showProfile(){
      $data= $this->ProfileService->getParentProfile();
      return $data;


    }
}
