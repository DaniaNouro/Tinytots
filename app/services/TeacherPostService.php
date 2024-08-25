<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Teacher;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class TeacherPostService{

    public function PublishPosts($request)
    {
        if (Auth::user()->hasRole('teacher')) {
                $teacherId=$request['teacher_id'];
                $teacher = Teacher::find($teacherId);

                if ($teacher) {
                    $content = $request['content'];
                    $scope = $request['scope'];
    
                    $post = [
                        'teacher_id' => $teacherId,
                        'content' => $content,
                        'scope' => $scope
                    ];
                    $post = Post::create($post);  
    
                    return ['data' => $post, 'message' => "Published successfully"];
                } else {
                    return ['data' => [], 'message' => "Teacher Not Found"];
                }
            }
        else{
                return ['data' => [], 'message' => "you are not allowed to do this"];
        }
        
    }
}
