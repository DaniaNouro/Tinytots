<?php
namespace App\Services\ParentService;
use App\Models\Parentt;
use Illuminate\Support\Facades\Auth;

class ParentProfileService{

  public function getParentProfile(){

  $parentId=Auth::user()->id;
  $parent=Parentt::findOrFail($parentId);
  $user=$parent->user;
  return $parent;
  }
  

}