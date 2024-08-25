<?php

namespace App\Http\Controllers;
use App\Services\LikeService;
use Illuminate\Http\Request;


class LikeController extends Controller
{
    protected $LikeService;
    public function __construct(LikeService $LikeService)
    {
        $this->LikeService=$LikeService;
    }
/*_________________________________________________________*/

    public function like($postId){
     $data= $this->LikeService->likePost($postId);
     return $data;
    }

/*_________________________________________________________*/    
    public function unLike($postId){
        $data= $this->LikeService-> unlikePost($postId);
        return $data;
    }
/*_________________________________________________________*/   
public function getCountLikes($postId){
      $data= $this->LikeService->getLikeCount($postId);
      return $data;

}

}
