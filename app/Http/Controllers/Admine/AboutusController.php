<?php

namespace App\Http\Controllers\Admine;

use App\Models\User;
use App\Models\Admin;
use App\Models\Admine;
use Illuminate\Http\Request;
use App\Traits\RenameMediaTrait;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AboutusController extends Controller
{
    use RenameMediaTrait;
    public function updateProfile(Request $request)
    {
        
        $userId=Auth::id();
        $user=User::findOrFail($userId);
        $admine=$user->admine;

        $request->validate([
            'first_name' => 'sometimes|required|string|max:45',
            'last_name' => 'sometimes|required|string|max:45',
            'email' => 'sometimes|required|email|unique:users,email,' .$user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
                if($user->image != null){
                $path = Storage::disk('tinytots')->delete($user->image);
                }
                $filename = $this->generateFilename($request->image);
                $path = $request->image->storeAs('profile/admine',$filename,'tinytots');
                $user->image='images/'.$path;
        }

        $user->save();

        if ($request->has('first_name')) {
            $admine->first_name = $request->first_name;
        }

        if ($request->has('last_name')) {
            $admine->last_name = $request->last_name;
        }

        $admine->save();

        return response()->json(['status' => 1, 'message' => 'Profile updated successfully', 'data' => ['id'=>$user->id,'image'=>$user->image,'email'=>$user->email,'first_name'=>$admine->first_name,'last_name'=>$admine->last_name]], 200);
    }

    // تابع لعرض الملف الشخصي
    public function showProfile()
    {
        $userId=Auth::id();
        $user=User::findOrFail($userId);
        $admine=$user->admine()->first();
        return response()->json(['status' => 1, 'data' => [
            'id' =>  $user->id,
            'first_name' =>  $admine->first_name,
            'last_name' => $admine->last_name,
            'email' => $user->email,
            'image' => $user->image,
        ], 'message' => 'success'], 200);
    }
}
