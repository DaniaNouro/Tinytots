<?php

namespace App\Services;

use App\Mail\sendAccountInformation;
use App\Models\Parentt;
use App\Models\Student;
use App\Traits\EmailSenderTrait;
use App\Traits\PasswordGeneratorTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class AdmineStudentService{
  use PasswordGeneratorTrait,EmailSenderTrait;

    public function createAccountStudent($request)
    {
      if (Auth::user()->hasRole('admine')) {
         $tempCode=$this->generateRandomPassword(4);
         $parentUser =User::query()->where('email', $request['email'])->first();
         $parent= $parentUser->parent;
         $user = User::query()->create([
          'email' =>$request['email'],
          'password' => Hash::make($tempCode)
        ]);
  
        $student = Student::query()->create([
          'user_id' => $user->id,
          'first_name' => $request['first_name'],
          'last_name' => $request['last_name'],
          'gender' => $request['gender'],
          'date_of_birth' => $request['date_of_birth'],
          'address' => $request['address'],
          'deatails' => $request['deatails'],
          'level'=>$request['level'],
          'parentt_id' => $parent->id
        ]);
  
        $studentRole = Role::query()->where('name', 'student')->first();
        $user->assignRole($studentRole);
  
        $permissions = $studentRole->permissions()->pluck('name')->toArray();
        $user->givePermissionTo($permissions);
  
    //     //Load the user's roles and permissions
  
    //     //$user->load('roles', 'permissions');
  
    //     //Reload the user instance to get update roles and permissins
        $user = User::query()->find($user['id']);
    //    // $user = $this->appendRolesAndPermissions($user);
        $user['token'] = $user->createToken("token")->plainTextToken;
        $message = "Student Created Successfully";
        return ['user' => $student, 'message' => $message];
      } else {
        return ['user' => [], 'message' => "you are not allowed to do this"];
      }
    }
  
    /////////////////////////////////////////////////////////////////////
  
    public function editAccountStudent($request,$studentId)
    {
      if (Auth::user()->hasRole('admine')) {
  
        $student = Student::findOrFail($studentId);
        $user = $student->user;
  
        if (isset($request['email'])) {
          $user->email = $request['email'];
        }
        if (isset($request['password'])) {
          $user->password = Hash::make($request['password']);
        }
        $user->save();
        //?? if not request data save old data
        $student->update([
          'first_name' => $request['first_name'] ?? $student->first_name,
          'last_name' => $request['last_name'] ?? $student->last_name,
          'gender' => $request['gender'] ?? $student->gender,
          'date_of_birth' => $request['date_of_birth'] ?? $student->date_of_birth,
          'phone_number' => $request['phone_number'] ?? $student->phone_number,
          'address' => $request['address'] ?? $student->address,
          'deatails' => $request['deatails'] ?? $student->deatails,
          'level'=>$request['level'] ?? $student->level
        ]);
        $message = "Student Updated Successfully";
        return ['user' =>$student, 'message' => $message];
      } else {
        return ['user' => [], 'message' => "you are not allowed to do this"];
      }
    }
  //////////////////////////////////////////////////////////////////////////////////////////
  
  public function showAccountStudent($id)
  {
    if (Auth::user()->hasRole('admine')){
      $student = Student::findOrFail($id);
      if($student){
      $message='Informaion showed successfuly';
      return ['user' => $student, 'message' => $message];
      }else{
       $message='teacher is not fount please try again!!' ;
       return ['user' => $student, 'message' => $message];
      }
    }else{
      return ['user' => [], 'message' => "you are not allowed to do this"];
    }
  }

//////////////////////////////////////////////////////////////////////////////////////////////////////
 public function sendEmailContentCode($id){
  if (Auth::user()->hasRole('admine')){
    $student = Student::findOrFail($id);
    if($student){
    $user= $student->user;
    $userEmail= $student->user['email'];
    $userPassword =($student->id)+1000;
    $student->user['password']=Hash::make($userPassword);
    $user->save();
    try{
    $this->sendEmail($userEmail,new sendAccountInformation(null,$userPassword));
  }
    catch(Exception){
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