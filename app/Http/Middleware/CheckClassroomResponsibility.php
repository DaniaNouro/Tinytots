<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ClassRoomTeacher;
use Illuminate\Support\Facades\Auth;

class CheckClassroomResponsibility
{
    public function handle(Request $request, Closure $next)
    {
        $classroomId = $request->route('classroom');
        if (Auth::user()->hasRole('teacher')) {
            $teacherId = Auth::id();
            if (ClassRoomTeacher::where('class_room_id', $classroomId)
                               ->where('teacher_id', $teacherId)
                               ->exists()) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
