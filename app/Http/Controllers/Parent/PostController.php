<?php

namespace App\Http\Controllers\Parent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\ParentService\PostService;

class PostController extends Controller
{
    protected $postService;
    public function __construct(PostService $postService)
    {
        $this->postService=$postService;
    }
/*___________________________________________________________________________*/
    public function show($studentId){
        
    //   $validated=Validator::make(['student_id'],['numric|nullable'],['OOPs']);
    //     if($validated){
        $data= $this->postService->index($studentId);
        return $data;
        // }
        // else
        // return  $validated;
    }
}
