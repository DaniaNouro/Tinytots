<?php

namespace App\Services;

use App\Mail\sendAccountInformation;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\EmailSenderTrait;
use App\Traits\PasswordGeneratorTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Monolog\Handler\SwiftMailerHandler;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Throwable;
use Swift_TransportException;
//use Spatie\Permission\Contracts\Role;


class AdmineService
{
  use PasswordGeneratorTrait,EmailSenderTrait;
 
  
  public function createAccountTeacher($request)
  {
    if (Auth::user()->hasRole('admine')) {
      $user = User::query()->create([
        'email' => $request['email'],
        'password' => Hash::make($request['national_id'])
      ]);

      $teacher = Teacher::query()->create([
        'user_id' => $user->id,
        'first_name' => $request['first_name'],
        'last_name' => $request['last_name'],
        'gender' => $request['gender'],
        'date_of_birth' => $request['date_of_birth'],
        'phone_number' => $request['phone_number'],
        'address' => $request['address'],
        'details' => $request['details'],
        'national_id' => $request['national_id'],
      ]);

      $teacherRole = Role::query()->where('name', 'teacher')->first();
      $user->assignRole($teacherRole);

      $permissions = $teacherRole->permissions()->pluck('name')->toArray();
      $user->givePermissionTo($permissions);

      //Load the user's roles and permissions
     // $user->load('roles', 'permissions');
      //Reload the user instance to get update roles and permissins
      $user = User::query()->find($user['id']);
     // $user = $this->appendRolesAndPermissions($user);
      $user['token'] = $user->createToken("token")->plainTextToken;
      $message = "Teacher Created Successfully";
      $user=$teacherRole->name;
      $teacher->user=$user;
     // $teacher=$tempPassword;
      return ['user' => $teacher, 'message' => $message];
    } else {
      return ['user' => [], 'message' => "you are not allowed to do this"];
    }
  }

  /////////////////////////////////////////////////////////////////////

  public function editAccountTeacher($request,$teacherId)
  {
    if (Auth::user()->hasRole('admine')) {

      $teacher = Teacher::findOrFail($teacherId);
      $user = $teacher->user;

      if (isset($request['email'])) {
        $user->email = $request['email'];
      }
      if (isset($request['national_id'])) {
        $user->password = Hash::make($request['national_id']);
      }
      $user->save();
      //?? if not request data save old data
      $teacher->update([
        'first_name' => $request['first_name'] ?? $teacher->first_name,
        'last_name' => $request['last_name'] ?? $teacher->last_name,
        'gender' => $request['gender'] ?? $teacher->gender,
        'date_of_birth' => $request['date_of_birth'] ?? $teacher->date_of_birth,
        'phone_number' => $request['phone_number'] ?? $teacher->phone_number,
        'address' => $request['address'] ?? $teacher->address,
        'details' => $request['details'] ?? $teacher->details,
      ]);
      
     // $user->load('roles', 'permissions');
     // $user->load('roles');
      $message = "Teacher Updated Successfully";
      return ['user' => $teacher, 'message' => $message];
    } else {
      return ['user' => [], 'message' => "you are not allowed to do this"];
    }
  }
//////////////////////////////////////////////////////////////////////////////////////////

public function showAccountTeacher($id)
{
  if (Auth::user()->hasRole('admine')){
    $teacher = Teacher::findOrFail($id);
    if($teacher){
    $message='Informaion showed successfuly';
    return ['user' => $teacher, 'message' => $message];
    }else{
     $message='teacher is not fount please try again!!' ;
     return ['user' => $teacher, 'message' => $message];
    }
  }else{
    return ['user' => [], 'message' => "you are not allowed to do this"];
  }
}

////////////////////////////////////send Email Information/////////////////////////////////////////////////////////
public function sendEmailContentInformationTeacher($id){
  if (Auth::user()->hasRole('admine')){
    $teacher = Teacher::findOrFail($id);
    if($teacher){
    $user=$teacher->user;
    $userEmail=$user['email'];
    $userPassword=$teacher['national_id'];
    $user->password = Hash::make($userPassword); //update password
    $user->save();
    try{
    $this->sendEmail($userEmail,new sendAccountInformation($userEmail,$userPassword));
      } catch (Exception $e) {
          Log::error('حدث خطأ غير متوقع أثناء إرسال الإيميل: ' . $e->getMessage());
      }
    $message='Information sended successfuly';
    return ['user'=>[$userEmail,$userPassword],'message' => $message];
    }
  } else
  {
    return ['user' => [], 'message' => "you are not allowed to do this"];
  }
}
////////////////////////////////////////////////////////////////////////////////////////////////
//show all teachers from table usesr image and email 

}



// public function appendRolesAndPermissions($user)
  // {

  //   //$user->roles = $user->roles->pluck('name');
  //  // $user->permissions = $user->permissions->pluck('name');
  //   // $roles = [];
  //   // foreach ($user->roles as $role) {
  //   //   $roles[] = $role->name;
  //   // }
  //   // unset($user['roles']);
  //   // $permissoins = [];
  //   // foreach ($user->permissions as $permission) {

  //   //   $permissoins[] = $permission->name;
  //   // }
  //   // unset($user['permissoins']);
  //   return $user;
  // }