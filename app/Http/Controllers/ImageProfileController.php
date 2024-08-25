<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Http\Responces\Response;
use App\Models\User;
use App\Services\imageProfileService;
use Illuminate\Http\Request;
use Spatie\Permission\Contracts\Role;

class ImageProfileController extends Controller
{

    protected $ImageProfileService;

    public function __construct(imageProfileService $imageProfileService)
    {
        $this->ImageProfileService = $imageProfileService;
    }
////////////////////////////////////////////////////////////////////////////////////////////////
    // public function insertImage(ImageRequest $request)
    // {
    //     $data = [];
    //     $user = auth()->user();
    //     $roleName = $user->roles()->first()->name;
    //     if ($request->hasFile('image')) {
           
    //      var_dump($data= $this->ImageProfileService->insertImage($request, $roleName));
    //         if($data['code']=='200'){
    //          $this->ImageProfileService->storeImageInDatabase($data['path'], auth()->user()->id);
    //          return  Response::Success($data['path'],$data['message'],$data['code']);
    //         }else{
    //          if($data['code']=='500'){
    //             return Response::Error($data['path'],$data['message'],$data['code']);
    //          }} 
    //         } else 
    //         {
    //             return Response::Validation(null, 'No image file found in the request', 400);
    //         }
       
    //     }

        public function insertImage(ImageRequest $request)
{
    $data = [];
    $user = auth()->user();
    $roleName = $user->roles()->first()->name;
    if ($request->hasFile('image')) {
        $data = $this->ImageProfileService->insertImage($request, $roleName); //  نُعطي  الِ  قيمة  $data  لِ  نتيجة  الِ  طريقة  insertImage 
        
        if ($data['code'] == '200') { 
            $this->ImageProfileService->storeImageInDatabase($data['path'], auth()->user()->id);
            return Response::Success('images/'.$data['path'], $data['message'], $data['code']);
        } else {
            if ($data['code'] == '500') {
                return Response::Error($data['path'], $data['message'], $data['code']);
            }
        }
    } else {
        return Response::Validation(null, 'No image file found in the request', 400);
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function editImage(ImageRequest $request)
    {
        $data = [];
        if ($request->hasFile('image')) {
            $data= $this->ImageProfileService->editImage($request);
           
            if($data['code']=='200'){
             $this->ImageProfileService->updatedImageInDatabase($data['path'], auth()->user()->id);
             return  Response::Success('images/'.$data['path'],$data['message'],$data['code']);
            }else{
             if($data['code']=='500'){
                return Response::Error($data['path'],$data['message'],$data['code']);
             }} 
            
            // {
            //  return Response::Validation(null, 'No image Inserted', 400);
            // }
    }
    return $data;
}
    ///////////////////////////////////////////////////////////////////////////////////////////////
    public function deleteImage()
    {
     $data=[];
     $data= $this->ImageProfileService->deleteImage();
     if($data['code']=='200'){
        return  Response::Success($data['path'],$data['message'],$data['code']);
       }else{
        if($data['code']=='500'){
           return Response::Error($data['path'],$data['message'],$data['code']);
        }
    } 
    }
    //////////////////////////////////////////////////////////////////////////////////////////////
    public function showImage()
    {
        $data=[];
        $data =$this->ImageProfileService->showImage();
        return Response::Success($data['user'],$data['message']);
        
    }
}
