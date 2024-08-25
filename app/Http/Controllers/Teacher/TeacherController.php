<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;

class TeacherController extends Controller
{
    public function showProfile(Request $request)
    {
        // Get the authenticated teacher
        $teacher = Teacher::where('user_id', $request->user()->id)->firstOrFail();

        // Get the user data
        $user = User::find($teacher->user_id);

        // Prepare the profile data
        $profileData = [
            'name' => $teacher->first_name . ' ' . $teacher->last_name,
            'gender'=>$teacher->gender,
            'date_of_birth'=>$teacher->date_of_birth,
            'phone_number'=>$teacher->phone_number,
            'address'=>$teacher->address,
            'details'=>$teacher->details,
            'national_id'=>$teacher->national_id,
            'email' => $user->email,
            'image' => $user->image, 
          
        ];

        return response()->json($profileData);
    }
}
