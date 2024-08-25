<?php

use App\Http\Controllers\ImageProfileController;
use App\Http\Controllers\Parent\SubjectController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Student\EvaluationController;
use App\Http\Controllers\Student\HomeworkController;
use App\Http\Controllers\Student\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::controller(StudentController::class)->group(function(){
    Route::post('student/login','login')->name('student.login');
    Route::group(['prefix' => 'student','middleware' => ['auth:sanctum']], function () {
/*_____________________________________________________________________________________________________________________________________________________________________________________*/
Route::get('attendance', [AttendanceController::class, 'ReturnAttendanceOfStudent'])->middleware('can:ReturnAttendanceOfStudent');

/*_____________________________________________________________________________________________________________________________________________________________________________________*/
Route::get('ReturnDateEvaluation', [EvaluationController::class, 'ReturnDateEvaluation'])->middleware('can:ReturnDateEvaluation');
Route::post('evaluation', [EvaluationController::class, 'EvaluationStudentInSpecificDate'])->middleware('can:EvaluationStudentInSpecificDate');
Route::get('sumPoint', [EvaluationController::class, 'SumPointOfStudent'])->middleware('can:SumPointOfStudent');
/*_____________________________________________________________________________________________________________________________________________________________________________________*/
Route::post('uploadHomework/{id}', [HomeworkController::class, 'uploadHomeworkstudent'])->middleware('can:uploadHomeworkstudent');
Route::post('updateHomework/{id}', [HomeWorkController::class, 'updateHomeworkstudent'])->middleware('can:updateHomeworkstudent');
Route::get('deleteHomework/{id}', [HomeWorkController::class, 'deleteHomeworkStudent'])->middleware('can:deleteHomeworkStudent');
Route::get('ReturnAllHomeworkToThisClass', [HomeWorkController::class, 'ReturnAllHomeworkToThisClass'])->middleware('can:ReturnAllHomeworkToThisClass');
Route::get('ReturnAllHomeworkThatUploadedByStudent', [HomeWorkController::class, 'Returnhomeworkstudentthatuploaded'])->middleware('can:Returnhomeworkstudentthatuploaded');

Route::post('insertImage', [ImageProfileController::class, 'insertImage'])->middleware('can:insertImage');
Route::get('profile', [StudentController::class, 'profileStudent'])->middleware('can:profileStudent');
Route::get('showSubjects', [SubjectController::class, 'showAllSubjects'])->middleware('can:showSubjects');
});


});

