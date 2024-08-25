<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Post_visibility;
use App\Models\Student;
use App\Traits\RenameMediaTrait;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PostService
{
    use RenameMediaTrait;

    public function index()
    {

        $user = Auth::user();
        $postsQuery = Post::query();

        if ($user->hasRole('admine')) {
            $postsQuery->forAdmin();
        } elseif ($user->hasRole('teacher')) {
            $postsQuery->forTeachers();
        } elseif ($user->hasRole('student')) {
            $postsQuery->forStudents();
        }

        $posts = $postsQuery->with(['user' => function ($query) {
            $query->with(['teacher', 'student', 'parent', 'admine']);
        }])->get();

        $response = $posts->map(function ($post) {
            $user = $post->user;
            $userDetails = $this->getUserDetails($user);

            return [
                'id' => $post->id,
                'visibility'=>$post->visibility,
                'title' => $post->title,
                'content' => $post->content,
                'media_path' => $post->media_path,
                'created_at' => Carbon::parse($post->created_at)->locale('en')->translatedFormat('d F Y H:i'), // تنسيق التاريخ
                'user' => $userDetails,
            ];
        });

        return response()->json(['message' => 'Posts showed successfully', 'posts' => $response]);
    }

        // $user = Auth::user();
        // $postsQuery = Post::query();
        // if ($user->hasRole('admin')) {
        //     $postsQuery = Post::forAdmin();
        // } elseif ($user->hasRole('teacher')) {
        //     $postsQuery = Post::forTeachers();
        // } elseif ($user->hasRole('student')) {
        //     $postsQuery = Post::forStudents();
        // } 
        // $posts = $postsQuery->with(['user' => function($query) {
        //     $query->with(['teacher', 'student', 'parent', 'admine']);
        // }])->get();
    
        // $response = $posts->map(function ($post) {
        //     $user = $post->user;
        //     $userDetails = $this->getUserDetails($user);
    
        //     return [
        //         'id' => $post->id,
        //         'title' => $post->title,
        //         'content' => $post->content,
        //         'media_path' => $post->media_path,
        //         'created_at' => Carbon::parse($post->created_at)->locale('en')->translatedFormat('d F Y H:i'), // تنسيق التاريخ
        //         'user' => $userDetails,
        //     ];
        // });
    
        // return response()->json(['message' => 'Posts showed successfully', 'posts' => $response]);
    
  //////////////////////////////////////////////////////////////////////////////////////
    // public function index()
    // {
    //     $user = Auth::user();
    //     $posts = Post::query();
    //     if ($user->hasRole('admine')) {
    //      $posts = Post::forAdmin()->get();
         
    //     } elseif ($user->hasRole('teacher')) {
    //         $posts = Post::forTeachers()->get();
    //     } elseif ($user->hasRole('student')) {
    //         $posts = Post::forStudents()->get();
    //     } elseif ($user->hasRole('parent')) {
    //         $studentClasses = $user->student->classes->pluck('id');
    //         $posts = Post::forParents($studentClasses)->get();
    //     }
    //     return response()->json(['message' => 'Posts showed successfully', 'post' => $posts]);
    // }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
    public function create(Request $request)
    {
        $user = Auth::user();
        $mediaPath = null;

        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $mediaPath = $this->generateFilename($media);
            $path = $media->storeAs('posts', $mediaPath, 'tinytots');
        }
        $post = Post::create([
            'user_id' => $user->id,
            'title' => $request['title'],
            'content' => $request['content'] ?? null,
            'media_path' =>  $path??null,
            'visibility' => $request['visibility']
        ]);

        if ($user->hasRole('teacher') && $request->has('classes')) {
            foreach ($request->classes as $classId) {
                Post_visibility::create([
                    'post_id' => $post->id,
                    'class_id' => $classId,
                ]);
            }
        }



        return response()->json(['message' => 'Post created successfully', 'post' => $post]);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    public function update($postId, Request $request)
{
    $user = Auth::user();
    $post = Post::findOrFail($postId);

    if ($post->user_id !== $user->id) {
        abort(403, 'Unauthorized action.');
    }

    $data = [
        'title' => $request->title ?? $post->title,
        'content' => $request->content ?? $post->content,
        'visibility' => $request->visibility ?? $post->visibility,
    ];

    if ($request->hasFile('media')) {
        if ($post->media_path) {
          //delete old image
            Storage::disk('tinytots')->delete($post->media_path);
        }
        $media = $request->file('media');
        $mediaPath = $this->generateFilename($media);
        $path = $media->storeAs('posts', $mediaPath, 'tinytots');
        $data['media_path'] = $path;
    }
    $post->update($data);
    if ($user->hasRole('teacher') && $request->has('classes')) {
        Post_visibility::where('post_id', $post->id)->delete();
        foreach ($request->classes as $classId) {
            Post_visibility::create([
                'post_id' => $post->id,
                'class_id' => $classId
            ]);
        }
    }
    return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
}
    ////////////////////////////////////////////////////////////////////////////////////////////////
    public function delete($postId)
    {
        $user = Auth::user();
        $post = Post::findOrFail($postId);
        if ($post->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($post->media_path) {
            $imagePath=$post->media_path;
            $path = Storage::disk('tinytots')->delete('posts/'.$imagePath);
        }
       $post->delete();
        return response()->json(['message' => 'Post deleted successfully.']);
       
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////

    private function getUserDetails($user)
{
    if ($user->teacher) {
        return [
            'first_name' => $user->teacher->first_name,
            'last_name' => $user->teacher->last_name,
            'profile_image' => $user->image ?? '',
        ];
    } elseif ($user->admine) {
        return [
            'first_name' => $user->admine->first_name,
            'last_name' => $user->admine->last_name,
            'profile_image' => $user->image ?? '',
        ];
    }
    
    return null;
}
}
