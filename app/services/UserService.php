<?php
namespace App\Services;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use APP\Services\AdmineService;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService{

//Dependency Injection
 protected $admineService;
  public function __construct(AdmineService $admineService)
  {
    $this->admineService=$admineService;

  }

  public function login($request):array
  {
    $user=User::query()->where('email',$request['email'])->first();
    if(!is_null($user)){
      if(!Auth::attempt($request->only(['email','password']))){
        $message='User email & password does not match with our record';
        $code=401;
      }
   else{
   // $user=$this->admineService->appendRolesAndPermissions($user);
    $user['token']=$user->createToken("token")->plainTextToken;
    $message='user login successfuly';
    $code=200;
   }
    }else{
$message='user Not Found';
$code=404;
    }
     
return['user'=>$user,'message'=>$message,'code'=>$code];
  }


  public function logout():array{
$user=Auth::user();

if(!is_null($user)){
 Auth::user()->currentAccessToken()->delete();
 $message='logout Successfuly';
 $code=200;
}else{
 $message='Invalid Token';
 $code=404;
}
  return['user'=>$user,'message'=>$message,'code'=>$code];
}

}