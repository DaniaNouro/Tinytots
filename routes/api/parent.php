<?php

use Illuminate\Http\Request;
use App\Services\CalendarService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Parent\ChatController;
use App\Http\Controllers\Parent\PostController;
use App\Http\Controllers\ImageProfileController;
use App\Http\Controllers\Parent\ProfileController;
use App\Http\Controllers\Parent\SubjectController;
use App\Http\Controllers\Parent\CalendarController;
use App\Http\Controllers\Parent\ChildrenController;
use App\Http\Controllers\Parent\EvaluationAndAttendanceController;

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

Route::group(['prefix' => 'parent','middleware' => ['auth:sanctum']], function () {
    Route::get('showChildrens', [ChildrenController::class, 'getStudentByParentId'])->middleware('can:showChildrens');
    Route::get('showCountChildrens', [ChildrenController::class, 'getCountStudents'])->middleware('can:showCountChildrens');
    Route::get('showProfile',[ProfileController::class,'showProfile'])->middleware('can:showProfile');
    Route::get('showTimeTable',[ProfileController::class,'showProfile'])->middleware('can:showTimeTable');
    Route::get('posts/showPosts/{studentId}',[PostController::class,'show'])->middleware('can:showPosts');
    Route::post('get-calendar-data/{studentId}',[CalendarController::class,'index'])->middleware('can:get-calendar-data');
    Route::get('showSubjects/{studentId}',[SubjectController::class,'showAllSubjects'])->middleware('can:showSubjects'); 
   
    Route::post('showEvaluations/{studentId}',[EvaluationAndAttendanceController::class,'returnEvaluationforStudent'])->middleware('can:showEvaluations');
    Route::post('showAttendances/{studentId}',[EvaluationAndAttendanceController::class,'returnAttendanceforStudent'])->middleware('can:showAttendance');;
    // اشعار عند غياب الطالب 
    Route::post('insertImageProfile',[ImageProfileController::class,'insertImage'])->middleware('can:insertImageProfile');
    Route::post('EditImageProfile',[ImageProfileController::class,'editImage'])->middleware('can:EditImageProfile');
    Route::Get('showImageProfile',[ImageProfileController::class,'showImage'])->middleware('can:showImageProfile');
    Route::delete('deleteImageProfile',[ImageProfileController::class,'deleteImage'])->middleware('can:deleteImageProfile');

    Route::post('chat', [ChatController::class, 'store']);
    Route::get('chat/{teacherId}', [ChatController::class, 'index']);

    
    Route::get('messages/{teacherId}', [ChatController::class, 'index']);
   // Route::post('messages', [MessageController::class, 'store']);

    Route::get('getTeachers/{studentId}', [ChatController::class, 'getTeacher']);
});