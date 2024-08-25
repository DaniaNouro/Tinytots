<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admine\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::controller(AuthController::class)->group(function(){
   
    Route::post('login','login')->name('user.login');
    
Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::get('logout','logout')->name('user.logout');
    Route::post('posts/addComment/{posstId}',[CommentController::class,'addComment'] );

    Route::delete('posts/deleteComment/{commentId}',[CommentController::class,'deleteComment'] );
    Route::put('posts/updateComment/{commentId}',[CommentController::class,'updateComment'] );
    Route::get('posts/showComments/{postId}',[CommentController::class,'showComments'] );
    Route::get('posts/countComments/{postId}',[CommentController::class,'getCommentCount'] );

    Route::post('posts/like/{postId}', [LikeController::class,'like']);
    Route::delete('posts/unlike/{postId}',[LikeController::class,'unLike']);
    Route::get('posts/countLike/{postId}', [LikeController::class,'getCountLikes']);
    Route::get('posts/showPosts',[PostController::class,'show']);


Route::get('report/teachers-count', [ReportController::class,'getTeachersCountReport']);
Route::get('report/students-count-per-class', [ReportController::class,'getStudentsCountPerClassReport']);
Route::get('report/daily-attendance', [ReportController::class,'getDailyAttendanceReport']);
Route::get('report/monthly-attendance/{month}/{year}', 'ReportController@getMonthlyAttendanceReport');
Route::get('report/students-performance', 'ReportController@getStudentsPerformanceReport');

    
});



});
