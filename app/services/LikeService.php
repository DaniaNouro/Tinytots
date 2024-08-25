<?php
namespace App\Services;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeService{

    public function likePost($postId)
{
    $userId = Auth::id();
    $existingLike = Like::where('user_id', $userId)->where('post_id', $postId)->first();
    
    if (!$existingLike) {
        $like = Like::create([
            'user_id' => $userId,
            'post_id' => $postId,
        ]);
    }
    
    return response()->json(['message' => 'Post liked successfully']);
}
 /*_______________________________________________________________________________________*/
    public function unlikePost($postId)
    {
        $like = Like::where('user_id', Auth::id())->where('post_id', $postId)->first();
    
        if (!$like) {
            abort(404, 'Like not found.');
        }
    
        $like->delete();
    
        return response()->json(['message' => 'Post unliked successfully']);
    }
/*_________________________________________________________________________________________*/    
    public function getLikeCount($postId)
    {
        $count = Like::where('post_id', $postId)->count();
    
        return response()->json(['like_count' => $count]);
    }

}