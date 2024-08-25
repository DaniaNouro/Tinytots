<?php

namespace App\Http\Controllers;
//use App\Http\Reponces\Response;
//use App\Http\Reponces;

use App\Http\Responces\Response;
use App\Http\Requests\TeacherSignupRequest;
use App\Http\Requests\UserLoginRequest;
use App\Services\AdmineService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    protected $admineService;
    protected $userService;

    public function __construct(AdmineService $admineService,UserService $userService)
    {
        $this->admineService =$admineService;
        $this->userService = $userService;
    }
    
public function login(UserLoginRequest  $request){
    $data=[];
    try{
     $data =$this->userService->login($request);
    return Response::Success($data['user'],$data['message'],$data['code']);
    }catch(Throwable $th){
    $message=$th->getMessage();
    return Response::Error($data,$message);
    }
   
   }

   public function logout():JsonResponse{
    $data=[];
    try{
     $data =$this->userService->logout();
    return Response::Success($data['user'],$data['message'],$data['code']);
    }catch(Throwable $th){
    $message=$th->getMessage();
    return Response::Error($data,$message);
    }
   
   }

}

//glpat-XN5h74m1eSpmh7D3vYLR