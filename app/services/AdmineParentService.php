<?php

namespace App\Services;

use App\Mail\sendAccountInformation;
use App\Traits\EmailSenderTrait;
use App\Traits\PasswordGeneratorTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Parentt;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
class AdmineParentService{
  use PasswordGeneratorTrait,EmailSenderTrait;
    protected $admineService;
  
    public function __construct(AdmineService $admineService)
    {
      $this->admineService=$admineService;
    }
  
    public function createAccountParent($request)
    {
      if (Auth::user()->hasRole('admine')) {
        $user = User::query()->create([
          'email' => $request['email'],
          'password' => Hash::make($request['national_id'])
        ]);
    
        $parent = Parentt::query()->create([
          'user_id' => $user->id,
          'father_first_name' => $request[ 'father_first_name'],
          'father_last_name' => $request['father_last_name'],
          'mother_first_name' => $request[ 'mother_first_name'],
          'mother_last_name'=> $request[ 'mother_last_name'],
          'father_phone_number' => $request[  'father_phone_number'],
          'mother_phone_number' => $request['mother_phone_number'],
          'national_id' => $request['national_id'],
        ]);
  
        $parentRole =Role::query()->where('name', 'parent')->first();
        $user->assignRole($parentRole);
  
        $permissions = $parentRole->permissions()->pluck('name')->toArray();
        $user->givePermissionTo($permissions);
  
        //Load the user's roles and permissions
  
       // $user->load('roles', 'permissions');
  
        //Reload the user instance to get update roles and permissins
        $user = User::query()->find($user['id']);
       // $user = $this->admineService->appendRolesAndPermissions($user);
        $user['token'] = $user->createToken("token")->plainTextToken;
        $user=$parentRole->name;
        $parent->user=$user;
        $message = "Parent Created Successfully";
        return ['user' => $parent, 'message' => $message];
      } else {
        return ['user' => [], 'message' => "you are not allowed to do this"];
      }
    }
    ////////////////////////////////////////////////////////////////////////
  public function editAccountParent($request,$parentId)
  {
    if (Auth::user()->hasRole('admine')) {
      $parent = Parentt::findOrFail($parentId);
      $user = $parent->user;

      if (isset($request['email'])) {
        $user->email = $request['email'];
      }
      if (isset($request['national_id'])) {
        $user->password = Hash::make($request['national_id']);
      }
      $user->save();
      //?? if not request data save old data
      $parent->update([
        'father_first_name' => $request[ 'father_first_name']?? $parent->father_first_name,
        'father_last_name' => $request['father_last_name']?? $parent->father_last_name,
        'mother_first_name' => $request[ 'mother_first_name']?? $parent->mother_first_name,
        'mother_last_name'=> $request[ 'mother_last_name']?? $parent->mother_last_name,
        'father_phone_number' => $request[  'father_phone_number']?? $parent->father_phone_number,
        'mother_phone_number' => $request['mother_phone_number']?? $parent->mother_phone_number,
        'national_id' => $request['national_id']?? $parent->national_id,
      ]);
     // $user->load('roles', 'permissions');
     // $user->load('roles');
      $message = "Parent Updated Successfully";
      return ['user' => $parent, 'message' => $message];
    } else {
      return ['user' => [], 'message' => "you are not allowed to do this"];
    }
  }
  
  //////////////////////////////////////////////////////////////////////////////////////////////////////

  public function showAccountParent($id)
{
  if (Auth::user()->hasRole('admine')){
    $parent = Parentt::findOrFail($id);
    if($parent){
    $message='Informaion showed successfuly';
    return ['user' => $parent, 'message' => $message];
    }else{
     $message='parent is not fount please try again!!' ;
     return ['user' => $parent, 'message' => $message];
    }
  }else{
    return ['user' => [], 'message' => "you are not allowed to do this"];
  }
}
////////////////////////////////////////////////////////////////////////////////////////////////////////
public function sendEmailContentInformationParent($id){
  if (Auth::user()->hasRole('admine')){
    $parent = Parentt::findOrFail($id);
    if($parent){
    $user=$parent->user;
    $userEmail=$parent->user['email'];
    $userPassword=$parent['national_id'];
    $user->password = Hash::make($userPassword); //update password
    $user->save();
    try{
    $this->sendEmail($userEmail,new sendAccountInformation($userEmail,$userPassword));}
    catch(Exception $ex){
      Log::error('Network error while sending email');
    }
    $message='Information sended successfuly';
    return ['user'=>[$userEmail,$userPassword],'message' => $message];
    }
  } else
  {
    return ['user' => [], 'message' => "you are not allowed to do this"];
  }
}
    






}