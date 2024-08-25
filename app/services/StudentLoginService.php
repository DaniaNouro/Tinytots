<?php
namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
class StudentLoginService{

    public function login($request):array
    { 
      $users=User::all();
      $user=null;
      foreach($users as $x)
      {
        if(Hash::check($request['password'],$x->password)){
          $user=$x;
          break;}
      }
      if(is_null($user)){
        $message='user Not Found';
        $code=404;
      }else{
      $studentRole = Role::query()->where('name', 'student')->pluck('name');
      $user['role']= $studentRole;
      $user['token']=$user->createToken("token")->plainTextToken;
      $message='User login successfuly';
      $code=200;
     }
    
  return['user'=>$user,'message'=>$message,'code'=>$code];
      }
}