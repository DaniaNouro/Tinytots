<?php

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ImageProfileController;
use App\Http\Controllers\Teacher\ChatController;
use App\Http\Controllers\Teacher\SortController;
use App\Http\Controllers\Teacher\GroupController;
use App\Http\Controllers\Admine\AgeGroupController;
use App\Http\Controllers\Teacher\SubjectController;
use App\Http\Controllers\Teacher\CalendarController;
use App\Http\Controllers\Teacher\HomeWorkController;
use App\Http\Controllers\Teacher\ClassRoomController;
use App\Http\Controllers\Teacher\EvaluationController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Teacher\ReportController;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('teacher/recordAttendance',[TeacherAttendanceController::class, 'takeAttendance'])/*->middleware('can:takeAttendance')*/;
    Route::post('teacher/updateAttendance',[TeacherAttendanceController::class, 'updateAttendance']);
    Route::post('teacher/recordAttendanceForStudents',[TeacherAttendanceController::class, 'takeMultipleAttendance'])/*->middleware('can:takeMultipleAttendance')*/;
    Route::post('teacher/returnAttendance',[TeacherAttendanceController::class,'AttendanceOnASpecificDate'])/*->middleware('can:AttendanceOnASpecificDate')*/;
    Route::post('teacher/showStudents',[TeacherAttendanceController::class,'showStudents'])/*->middleware('can:showStudents')*/;
    Route::post('teacher/RecordStudent',[TeacherAttendanceController::class,'AttendanceRecordForTheStudent'])/*->middleware('can:AttendanceRecordForTheStudent')*/;
    Route::get('teacher/getStudentsDataWithPoints/{id}',[TeacherAttendanceController::class,'getStudentsDataWithPoints']);
/*____________________________________________________________________________________________________________________________________________________________________________________*/
//دانيا لا تنسي تعدلي ال api
    Route::post('teacher/evaluationStudent',[EvaluationController::class,'StudentEvaluation'])/*->middleware('can:StudentEvaluation')*/;
    Route::get('teacher/randomEvaluation/{id}',[EvaluationController::class,'RandomEvaluation'])/*->middleware('can:RandomEvaluation')*/;
    Route::get('teacher/returnStudentEvaluations/{id}',[EvaluationController::class,'ReturnStudentEvaluations'])/*->middleware('can:ReturnStudentEvaluations')*/;
    Route::post('teacher/StudentEvaluationInSpecificPeriod',[EvaluationController::class,'ReturnStudentEvaluationInSpecificPeriod'])/*->middleware('can:ReturnStudentEvaluationInSpecificPeriod')*/;
    Route::post('teacher/StudentEvaluationinspecificDay',[EvaluationController::class,'ReturnStudentsEvaluationsInSpecificDay'])/*->middleware('can:ReturnStudentsEvaluationsInSpecificDay')*/;
    Route::get('teacher/ReturnPointsOfStudent/{id}',[EvaluationController::class,'ReturnPointsOfStudent'])/*->middleware('can:ReturnPointsOfStudent')*/;


    Route::post('teacher/createPositivePoint',[EvaluationController::class,'GivingStudentsPositivePoints'])/*->middleware('can:GivingStudentsPositivePoints')*/;
    Route::post('teacher/createNeedworkPoint',[EvaluationController::class,'GivingStudentsNeedworkPoints'])/*->middleware('can:GivingStudentsNeedworkPonints')*/;
    Route::post('teacher/UpdatePositivePoint',[EvaluationController::class,'UpdatePositivePoint'])/*->middleware('can:UpdatePositivePoint')*/;
    Route::post('teacher/UpdateNeedworkPoint',[EvaluationController::class,'UpdateNeedworkPoint'])/*->middleware('can:UpdateNeedworkPoint')*/;
    Route::get('teacher/DelatePositivePoint/{id}',[EvaluationController::class,'DelatePositivePoint']);//->middleware('can:DelatePositivePoint');
    Route::get('teacher/DelateNeedWorkPoint/{id}',[EvaluationController::class,'DelateNeedWorkPoint']);//->middleware('can:ReturnStudentsPointsInSpecificDay');
    Route::get('teacher/ReturnPointsOfStudent/{id}',[EvaluationController::class,'ReturnPointsOfStudent'])->middleware('can:ReturnPointsOfStudent');
    Route::post('teacher/AllPoint',[EvaluationController::class,'ReturnAllPoint']);//->middleware('can:ReturnAllPoint');
    Route::post('teacher/count_points',[EvaluationController::class,'count_points']);
/*____________________________________________________________________________________________________________________________________________________________________________________*/
    Route::get('teacher/SortByAlpabet/{id}',[SortController::class,'SortByAlphabet'])/*->middleware('can:SortByAlphabet')*/;
    Route::get('teacher/SortByHighestPoints/{id}',[SortController::class,'SortByHighestPoints'])/*->middleware('can:SortByHighestPoints')*/;
    Route::get('teacher/SortByLowestPoints/{id}',[SortController::class,'SortByLowestPoints'])/*->middleware('can:SortByLowestPoints')*/;


/*____________________________________________________________________________________________________________________________________________________________________________________ */
 
/*____________________________________________________________________________________________________________________________________________________________________________________ */
    Route::post('teacher/createGroup',[GroupController::class,'createGroup'])/*->middleware('can:createGroup')*/;
    Route::post('teacher/UpdateStudent',[GroupController::class,'UpdateStudent'])/*->middleware('can:UpdateStudent')*/;
    Route::get('teacher/ReturnSDetailsOfGroup/{id}',[GroupController::class,'ReturnSDetailsOfGroup'])/*->middleware('can:ReturnStudentsOFGroup')*/;
    Route::post('teacher/UpdateGroup',[GroupController::class,'UpdateGroup'])/*->middleware('can:UpdateGroup')*/;
    Route::get('teacher/DelateGroup/{id}',[GroupController::class,'DelateGroup']);//->middleware('can:DelateGroup');
    Route::get('teacher/showTasks',[GroupController::class,'getTasks']);
    Route::get('teacher/AllGroupsOfClass/{id}',[GroupController::class,'ReturnAllGroupsToThisClass']);
    Route::get('teacher/AllTasks',[GroupController::class,'ReturnAllTasks']);//->middleware('can:ReturnAllTasks');
  /*__________________________________________________________________________________________________________________________________________________________________________________*/

    Route::post('teacher/uploadHomework',[HomeWorkController::class,'uploadHomework'])/*->middleware('can:uploadHomework')*/;
    Route::post('teacher/updateHomework/{id}',[HomeWorkController::class,'updateHomework'])/*->middleware('can:updateHomework')*/;
    Route::get('teacher/deleteHomework/{id}',[HomeWorkController::class,'deleteHomework'])/*->middleware('can:deleteHomework')*/;
    Route::get('teacher/returnAllHomeWork',[HomeWorkController::class,'returnAllHomeWork']);//->middleware('can:returnAllHomeWork');
    Route::get('teacher/returnHomeworksOfclassroom/{id}',[HomeWorkController::class,'returnHomeworksOfclassroom']);//->middleware('can:returnHomeworksOfclassroom');
    Route::get('teacher/numstduploadedhomework/{id}',[HomeWorkController::class,'NumOfStudentHasUploadedHomework']);//->middleware('can:NumOfStudentHasUploadedHomework');
    Route::get('teacher/numstuNotUploadedHomework/{classID}/{homeworkID}',[HomeWorkController::class,'NumOfStudentsNotUploadedHomework']);
    Route::get('teacher/getStudentsWithHomework/{id}',[HomeWorkController::class,'getStudentsWithHomework']);//->middleware('can:getStudentsWithHomework');
    Route::get('teacher/getStudentsThatNotUploadedHomework/{classID}/{homeworkID}',[HomeWorkController::class,'getStudentsThatNotUploadedHomework']);
    /*___________________________________________________________________________________________________________________________________________________________________________________________*/

    Route::post('teacher/CreateReports',[ReportController::class,'CreateReports']);//->middleware('can:CreateReports');
/*________________________________________________________________________________*/
Route::get('teacher/showClass/{age_group_id}',[ClassRoomController::class,'showClassForTeacher']);
Route::get('teacher/getAgeGroups',[AgeGroupController::class,'getAgeGroups']);
Route::get('teacher/get-calendar-data/{day}',[CalendarController::class,'index']);
Route::get('teacher/showSubjects',[SubjectController::class,'showAllSubjects']); 

Route::post('teacher/createPost',[PostController::class,'create']);
Route::put('teacher/updatePost/{postId}',[PostController::class,'update']);
Route::delete('teacher/deletePost/{postId}',[PostController::class,'delete']);


Route::post('teacher/insertImageProfile',[ImageProfileController::class,'insertImage']);
    Route::post('teacher/EditImageProfile',[ImageProfileController::class,'editImage']);
    Route::Get('teacher/showImageProfile',[ImageProfileController::class,'showImage']);
    Route::delete('teacher/deleteImageProfile',[ImageProfileController::class,'deleteImage']);

    // Route::post('teacher/send-message', [ChatController::class, 'store']);

    // Route::get('teacher/get_message/{conversationId}',[MessageController::class, 'index']);
    Route::post('teacher/chat', [ChatController::class, 'store']);
    Route::get('teacher/chat/{parentId}', [ChatController::class, 'index']);
    Route::get('teacher/messages/{conversationId}', [MessageController::class, 'index']);
    Route::get('teacher/showParents', [ChatController::class, 'getParentsForTeacher']);

});