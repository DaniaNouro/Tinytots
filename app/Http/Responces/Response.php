<?php
namespace App\Http\Responces;
use Illuminate\Http\JsonResponse;

class Response{

    public static function Success($data,$message=null,$code=200):JsonResponse{
      if($message==null){
        return response()->json([
          'status'=>1,
          'data'=>$data,
            ],$code);
      }else{
      return response()->json([
        'status'=>1,
        'data'=>$data,
        'message'=>$message,
      ],$code);
    }
  }
    public static function Error($data,$message=null,$code=500):JsonResponse{
      if($message==null){
        return response()->json([
          'status'=>0,
          'data'=>$data,
            ],$code);
      }else{
        return response()->json([
            'status'=>0,
            'data'=>$data,
            'message'=>$message,
          ],$code);
        }
    }
    public static function Validation($data,$message=null,$code=422):JsonResponse{
     
        return response()->json([
            'status'=>0,
            'data'=>$data,
            'message'=>$message,
          ],$code);
        }
    }


