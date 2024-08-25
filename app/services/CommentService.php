<?php
namespace App\Services;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentService{

    public function addComment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
    
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $postId,
            'content' => $request->content,
        ]);
    
        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment]);
    }
/*_______________________________________________________________________________*/
    public function updateComment(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
    
        $comment = Comment::findOrFail($commentId);
    
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    
        $comment->update([
            'content' => $request->content,
        ]);
    
        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment]);
    }
/*_______________________________________________________________________________*/
public function deleteComment($commentId)
{
    $comment = Comment::findOrFail($commentId);

    if ($comment->user_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    $comment->delete();

    return response()->json(['message' => 'Comment deleted successfully']);
}
/*_______________________________________________________________________________*/

public function show($postId) {
    $post = Post::findOrFail($postId);
    $comments = $post->comments()->with('user')->get();
    
    $commentsWithOwner = $comments->map(function($comment) {
        $user = $comment->user;
        $name = '';

        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            $name = $teacher->first_name . ' ' . $teacher->last_name;
        } elseif ($user->hasRole('admine')) {
            $admin = $user->Admine;
           $name =$admin->first_name . ' ' . $admin->last_name;
           
        } elseif ($user->hasRole('parent')) {
            $parent = $user->parentt;
            $name = $parent->father_first_name . ' ' . $parent->father_last_name;
        } else {
            $name = 'Unknown User';
        }

        return [
            'comment_id' => $comment->id,
            'content' => $comment->content,
            'user_name' => $name
        ];
    });

    return response()->json($commentsWithOwner);
}
/*_______________________________________________________________________________*/

public function getCommentCount($postId)
{
    
    $post = Post::findOrFail($postId);

   
    $commentCount = $post->comments()->count();

   
    return response()->json(['comment_count' => $commentCount]);
}
}