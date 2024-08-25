<?php
namespace App\Services;


use App\Models\User;
use App\Traits\RenameMediaTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class imageProfileService{
use RenameMediaTrait;
public function showImage()
{
    $id = Auth::id();
    $user = User::findOrFail($id);

    if ($user) {
        $userImage = $user->image;
        $message = 'Image showed successfully';
        return ['user' => $userImage, 'message' => $message,'code'=>'200'];
    } else {
        return ['path' => 'Null', 'message' => 'There is no user && image', 'code' => 404];
    }
}



public function insertImage($request, $roleName)
{
    $image = $request->file('image');
    $folder = $this->getFolderForRole($roleName);

    if (!empty($folder)) {
        $filename = $this->generateFilename($image);
        $path = $image->storeAs($folder,$filename,'tinytots');

        if ($path) { 
            return ['path' => $path, 'message' => 'Image inserted successfully', 'code' => 200];
        } else {
            return ['path' => 'Null', 'message' => 'Failed to upload image', 'code' => 500];
        }
    } else {
        return ['path' => 'Null', 'message' => 'Failed to determine folder for role', 'code' => 500];
    }
}
////////////////////////////////////////////////////////////////////////////////////////////
public function editImage($request)
{
    $user = auth()->user();
    $roleName = $user->roles()->first()->name;

    if ($request->hasFile('image')) {

        $image = $request->file('image');
        $folder = $this->getFolderForRole($roleName);
        $filename = $this->generateFilename($image);
        $result=$this->updatedImageInDatabase( $folder.$filename, $user->id);
        $path = $image->storeAs($folder, $filename, 'tinytots'); //  حفظ  الِ  صورة  في  الِ  مجلد  الِ  مُناسب

        if ($path) {
            return ['path' => $path, 'message' => 'Image edited successfully', 'code' => 200];
        } else {
            return ['path' => 'Null', 'message' => 'Failed to upload image', 'code' => 500];
        }
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////

public function deleteImage(){
    $user = auth()->user();
    $roleName = $user->roles()->first()->name;
    $image=$user->image;
    if($image != null){
        $imagePath=$image;
        $folder = $this->getFolderForRole($roleName);
       if($imagePath) {
       $path = Storage::disk('tinytots')->delete($imagePath);
            $this->deletImageFromDataBase($user->id);
           return ['path' => $path, 'message' => 'Image deleted successfully', 'code' => 200];
         }
    } else {
        return ['path' => 'Null','message' => 'there is not image to delete', 'code' => 500];
    }

////////////////////////////////////////////////////////////////////////////////////////////////////

}
public function storeImageInDatabase($path, $userId) {
    $user=User::findOrFail($userId);
    $user->image = 'images/'.$path;
    $user->save();
}
private function getFolderForRole($role) {
    switch ($role) {
        case 'teacher':
            return '/profile/teachers';
        case 'admine':
            return '/profile/admine';
        case 'student':
            return '/profile/students';
        case 'parent':
            return '/profile/parents';
        default:
            return null;
    }
}
//////////////////////////////////////////////////////////////////////////////////
public function generateFilename($image)
{
    return uniqid() . '.' . $image->getClientOriginalExtension();
}

//////////////////////////////////////////////////////////////////////////////////
public function updatedImageInDatabase($path,$userId){
    $user = User::find($userId);
    if ($user->image) {
        Storage::disk('tinytots')->delete($user->image);
    }

    $user->image =$path; 
    $user->save();

    return true;

}

//////////////////////////////////////////////////////////////////////////////////////////
private function deletImageFromDataBase($userId){
    $user=User::findOrFail($userId);
    $user->deleted($user->image);
    $user['image']=Null;
    $user->save();
}
}