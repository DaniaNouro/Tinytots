<?php

namespace App\Http\Controllers;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $CommentService;
    public function __construct(CommentService $CommentService)
    {
        $this->CommentService=$CommentService;
    }

    public function addComment(Request $request,$postId){
     $data= $this->CommentService->addComment ($request,$postId);
     return $data;

    }
/*____________________________________________________________________*/    
    public function updateComment(Request $request,$postId){

    $data= $this->CommentService->updateComment($request,$postId);
    return $data;
        
    }
/*____________________________________________________________________*/     
    public function deleteComment($commentId){
    
    $data= $this->CommentService->deleteComment($commentId);
    return $data;
    }
/*____________________________________________________________________*/     
    public function showComments($postId){
        $data= $this->CommentService->show($postId);
        return $data;
    
    }
/*____________________________________________________________________*/ 
public function getCommentCount($postId){

    $data=$this->CommentService->getCommentCount($postId);
    return $data;
}
/*____________________________________________________________________*/ 

}
