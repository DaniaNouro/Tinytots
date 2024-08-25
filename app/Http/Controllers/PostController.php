<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdmineUpdatePost;
use App\Http\Requests\TeacherUpdatePost;
use  App\Http\Requests\AdminePostRequest;
use Illuminate\Support\Facades\Validator;
use  App\Http\Requests\TeacherPostRequest;


class PostController extends Controller
{
    protected $postService;
    public function __construct(PostService $postService)
    {
        $this->postService=$postService;
    }
/*___________________________________________________________________________*/
    public function show(){
        
      $validated=Validator::make(['student_id'],['int|nullable'],['OOPs']);
        if($validated){
        $data= $this->postService->index();
        return $data;
        }
        else return  $validated;
    }
/*___________________________________________________________________________*/
    public function create(Request $request)
    {
        $user=Auth::user();
        if ($user->hasRole('admin')) {
            $validated = $request->validate((new AdminePostRequest())->rules());
        } else if ($user->hasRole('teacher')) {
            $validated = $request->validate((new TeacherPostRequest())->rules());
        }
       $data= $this->postService->create($request);
       return $data;

    }
/*___________________________________________________________________________*/
public function update($postId,Request $request)
{
    $user=Auth::user();
    $validated=false;
    if ($user->hasRole('admine')) {
        $validated = $request->validate((new AdmineUpdatePost())->rules());
    } else if ($user->hasRole('teacher')) {
        $validated = $request->validate((new TeacherUpdatePost())->rules());
    }
    if($validated){
   $data= $this->postService->update($postId,$request);
   return $data;
    }else{
        return $validated;
    }
}
/*___________________________________________________________________________*/
public function delete($postId){
    
    $data= $this->postService->delete($postId);
    return $data;

}
/*___________________________________________________________________________*/
}
