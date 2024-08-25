<?php
namespace App\Services\ParentService;

use App\Models\Post;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PostService{
    public function index($Id)
    {
        $user = Auth::user();
        $postsQuery = Post::query();
        
        if ($user->hasRole('parent')) {
            $student = Student::find($Id);
    
            if (!$student) {
                return response()->json(['message' => 'Student not found'], 404);
            }
    
            if ($student->parentt_id != $user->id) {
                return response()->json(['message' => 'Unauthorized access to student records'], 403);
            }
    
            // استخراج الصفوف للطالب
            $student=Student::find($Id)->first();
            $class = $student->class_rooms()->pluck('class_rooms.id')->toArray();
            $postsQuery = Post::forParents($class); 
        } else {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }
    
        $posts = $postsQuery->with(['user' => function($query) {
            $query->with(['teacher', 'student', 'parent', 'admine']);
        }])->get();
    
        $response = $posts->map(function ($post) {
            $user = $post->user;
            $userDetails = $this->getUserDetails($user);
    
            return [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'media_path' => $post->media_path,
                'created_at' => Carbon::parse($post->created_at)->locale('en')->translatedFormat('d F Y H:i'),
                'user' => $userDetails,
            ];
        });
    
        return response()->json(['message' => 'Posts showed successfully', 'posts' => $response]);
    }
    
//     public function index($Id)
//     {
//         $user = Auth::user();
//         $postsQuery = Post::query();
//         if ($user->hasRole('parent')) {
//             $studentId =$Id; // جلب معرف الابن من الطلب
//             if ($studentId) {
//                 $student = Student::findOrFail($studentId)->first();
//                if ($student->parentt_id == $user->id) { // تأكيد أن الابن ينتمي لهذا الأب
//                    $class =$student->class_rooms()->pluck('class_rooms.id')->toArray();
//                     $postsQuery = Post::forParents($class); 
//                 } else {
//                     return response()->json(['message' => 'Unauthorized access to student records'], 403);
//                 }
//             } else {
//                 return response()->json(['message' => 'Student ID is required'], 400);
//             }
//         $posts = $postsQuery->with(['user' => function($query) {
//             $query->with(['teacher', 'student', 'parent', 'admine']);
//         }])->get();
//         $response = $posts->map(function ($post) {
//             $user = $post->user;
//             $userDetails = $this->getUserDetails($user);
    
//             return [
//                 'id' => $post->id,
//                 'title' => $post->title,
//                 'content' => $post->content,
//                 'media_path' => $post->media_path,
//                 'created_at' => Carbon::parse($post->created_at)->locale('en')->translatedFormat('d F Y H:i'), // تنسيق التاريخ
//                 'user' => $userDetails,
//             ];
//         });
    
//         return response()->json(['message' => 'Posts showed successfully', 'posts' => $response]);
//     }
// }
   
        

    /*_________________________________________________________________________________*/
    
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