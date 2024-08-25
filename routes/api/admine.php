<?php

use Illuminate\Http\Request;
use App\Http\Responces\Response;
use App\Mail\sendAccountInformation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

use Yaza\LaravelGoogleDriveStorage\Gdrive;
use App\Http\Controllers\ImageProfileController;
use App\Http\Controllers\Admine\AboutusController;
use App\Http\Controllers\Admine\ExcelImportController;
use App\Http\Controllers\Admine\ParentSearchSortController;
use App\Http\Controllers\Admine\StudentSearchSortController;
use App\Http\Controllers\Admine\AdmineController as AdmineAdmineController;
use App\Http\Controllers\Admine\LessonController as AdmineLessonController;
use App\Http\Controllers\Admine\SubjectController  as AdmineSubjectController;
use App\Http\Controllers\Admine\AgeGroupController as AdmineAgeGroupController;
use App\Http\Controllers\Admine\CalendarController as AdmineCalendarController;
use App\Http\Controllers\Admine\ClassRoomController as AdmineClassRoomController;
use App\Http\Controllers\Admine\AdmineParentController as AdmineAdmineParentController;
use App\Http\Controllers\Admine\AdmineStudentController as AdmineAdmineStudentController;
use App\Http\Controllers\Admine\StudentEnrollmentController as AdmineStudentEnrollmentController;
use App\Http\Controllers\Admine\TeacherAssignmentController as AdmineTeacherAssignmentController;
use App\Http\Controllers\Admine\TeacherSearchSortController as AdmineTeacherSearchSortController;



Route::group(['middleware' => ['auth:sanctum']], function () {


    Route::put('admine/profile', [AboutusController::class, 'updateProfile']);
    Route::get('admine/profile', [AboutusController::class, 'showProfile']);





    Route::post('admine/insertImageProfile', [ImageProfileController::class, 'insertImage'])->middleware('can:insertImage');
    Route::post('admine/EdittImageProfile', [ImageProfileController::class, 'editImage'])->middleware('can:editImage');
    Route::Get('admine/showImageProfile', [ImageProfileController::class, 'showImage'])->middleware('can:showImage');
    Route::delete('admine/deleteImageProfile', [ImageProfileController::class, 'deleteImage'])->middleware('can:deleteImage');
    

    Route::post('admine/registerTeacher', [AdmineAdmineController::class, 'createAccountTeacher'])->middleware('can:createAccountTeacher');
    Route::post('admine/editAccountTeacher/{id}', [AdmineAdmineController::class, 'editAccountTeacher'])->middleware('can:editAccountTeacher');
    Route::get('admine/showAccountTeacher/{id}', [AdmineAdmineController::class, 'showAccountTeacher'])->middleware('can:showAccountTeacher');
    Route::get('admine/sendEmailAccountTeacher/{id}', [AdmineAdmineController::class, 'sendEmailContentInformationTeacher']);

    Route::post('admine/registerParent', [AdmineAdmineParentController::class, 'createAccountParent'])->middleware('can:createAccountParent');
    Route::post('admine/editAccountParent/{id}', [AdmineAdmineParentController::class, 'editAccountParent'])->middleware('can:editAccountParent');
    Route::get('admine/showAccountParent/{id}', [AdmineAdmineParentController::class, 'showAccountParent'])->middleware('can:showAccountParent');
    Route::get('admine/sendEmailAccountParent/{id}', [AdmineAdmineParentController::class, 'sendEmailContentInformationParent']);

    Route::post('admine/registerStudent', [AdmineAdmineStudentController::class, 'createAccountStudent'])->middleware('can:createAccountStudent');
    Route::put('admine/editAccountStudent/{id}', [AdmineAdmineStudentController::class, 'editAccountStudent'])->middleware('can:editAccountStudent');
    Route::get('admine/showAccountStudent/{id}', [AdmineAdmineStudentController::class, 'showAccountStudent'])->middleware('can:showAccountStudent');
    Route::get('admine/sendEmailCodeStudent/{id}', [AdmineAdmineStudentController::class, 'sendEmailContentCode'])->middleware('can:sendEmailContentCode');

    Route::get('admine/getAgeGroups', [AdmineAgeGroupController::class, 'getAgeGroups'])->middleware('can:getAgeGroups');
    Route::post('admine/createClass/{id}', [AdmineClassRoomController::class, 'createClass'])->middleware('can:createClass');
    Route::put('admine/editClass/{id}', [AdmineClassRoomController::class, 'editClass'])->middleware('can:editClass');
    Route::get('admine/showClass/{id}', [AdmineClassRoomController::class, 'showClass'])->middleware('can:showClass');
    Route::get('admine/showDetailsClass/{ClassId}', [AdmineClassRoomController::class, 'showDetailesClass'])->middleware('can:showDetailesClass');
    Route::post('admine/assignToClass', [AdmineTeacherAssignmentController::class, 'assignTeacherToClass'])->middleware('can:assignTeacherToClass');
    Route::get('admine/getResponsibleTeacher/{id}', [AdmineTeacherAssignmentController::class, 'getResponsibleTeachers'])->middleware('can:getResponsibleTeachers');
    Route::post('admine/classrooms/add-student', [AdmineStudentEnrollmentController::class, 'addStudentToClass'])->middleware('can:addStudentToClass');
    Route::get('admine/showClassbyTeacher/{Teacherid}', [AdmineClassRoomController::class, 'showClassbyTeacher'])->middleware('can:showClassbyTeacher');
    Route::delete('admine/deleteClass/{ClassId}', [AdmineClassRoomController::class, 'deleteClass']);





    Route::post('admine/createLesson', [AdmineLessonController::class, 'createLesson'])->middleware('can:createLesson');
    Route::put('admine/updateLesson/{lessonId}', [AdmineLessonController::class, 'updateLesson'])->middleware('can:updateLesson');
    Route::delete('admine/deleteLesson/{lessonId}', [AdmineLessonController::class, 'deleteLesson'])->middleware('can:deleteLesson');
    Route::get('admine/showLessons', [AdmineLessonController::class, 'showAllLessons'])->middleware('can:showAllLessons');
    Route::get('admine/calendar/{classid}', [AdmineCalendarController::class, 'index'])->middleware('can:index');

    Route::post('admine/uploadFile', [AdmineSubjectController::class, 'uploadSubject'])->middleware('can:uploadSubject');
    Route::put('admine/updateSubject/{subjectId}', [AdmineSubjectController::class, 'updateSubject'])->middleware('can:updateSubject');
    Route::delete('admine/deleteSubject/{subjectId}', [AdmineSubjectController::class, 'deleteSubject'])->middleware('can:deleteSubject');
    Route::get('admine/showAllSubjects', [AdmineSubjectController::class, 'showAllSubjects'])->middleware('can:showAllSubjects');
    Route::get('admine/showSubjects', [AdmineSubjectController::class, 'showSubjects'])->middleware('can:showSubjects');

    Route::post('admine/importStudent', [ExcelImportController::class, 'importStudents'])->middleware('can:importStudents');
    Route::post('admine/importTeacher', [ExcelImportController::class, 'importTeachers'])->middleware('can:importTeachers');
    Route::post('admine/importParent', [ExcelImportController::class, 'importParents'])->middleware('can:importParents');

    Route::post('admine/createPost', [PostController::class, 'create'])->middleware('can:createPost');
    Route::put('admine/updatePost/{postId}', [PostController::class, 'update'])->middleware('can:updatePost');
    Route::delete('admine/deletePost/{postId}', [PostController::class, 'delete'])->middleware('can:deletePost');

    Route::get('admine/searchByName/{name}', [AdmineTeacherSearchSortController::class, 'searchByName'])->middleware('can:searchByName');
    Route::get('admine/searchByEmail/{email}', [AdmineTeacherSearchSortController::class, 'searchByEmail'])->middleware('can:searchByEmail');
    Route::get('admine/searchByPhone/{phone}', [AdmineTeacherSearchSortController::class, 'searchByPhone'])->middleware('can:searchByPhone');
    Route::get('admine/sortAcs/{id?}', [AdmineTeacherSearchSortController::class, 'sortAcs'])->middleware('can:sortAcs');
    Route::get('admine/sortDesc/{id?}', [AdmineTeacherSearchSortController::class, 'sortDesc'])->middleware('can:sortDesc');



    //صيانة كاملة 
    Route::get('admine/searchByNameParent/{name}', [ParentSearchSortController::class, 'searchByName'])->middleware('can:searchByName');
    Route::get('admine/searchByEmailParent/{email}', [ParentSearchSortController::class, 'searchByEmail'])->middleware('can:searchByEmail');
    Route::get('admine/searchByPhoneParent/{phone}', [ParentSearchSortController::class, 'searchByPhone'])->middleware('can:searchByPhone');
    Route::get('admine/sortAcsParent/{id?}', [ParentSearchSortController::class, 'sortAcs'])->middleware('can:sortAcs');
    Route::get('admine/sortDescParent/{id?}', [ParentSearchSortController::class, 'sortDesc'])->middleware('can:sortDesc');

    Route::get('admine/searchByNameStudent/{name}', [StudentSearchSortController::class, 'searchByName'])->middleware('can:searchByName');
    Route::get('admine/searchByEmailStudent/{email}', [StudentSearchSortController::class, 'searchByEmail'])->middleware('can:searchByEmail');
    Route::get('admine/sortAcsStudent/{id?}', [StudentSearchSortController::class, 'sortAcs'])->middleware('can:sortAcs');
    Route::get('admine/sortDescStudent/{id?}', [StudentSearchSortController::class, 'sortDesc'])->middleware('can:sortDesc');




    Route::get('admine/showAllTeachers', [AdmineAdmineController::class, 'showAllTeacher'])->middleware('can:showAllTeacher');
    Route::get('admine/showDetailesTeachers', [AdmineAdmineController::class, 'showTeacherDetailes'])->middleware('can:showTeacherDetailes');

    Route::get('admine/showAllStudents', [AdmineAdmineStudentController::class, 'showAllStudent'])->middleware('can:showAllStudent');
    Route::get('admine/showDetailesStudents', [AdmineAdmineStudentController::class, 'showStudentDetailes'])->middleware('can:showStudentDetailes');

    Route::get('admine/showAllParents', [AdmineAdmineParentController::class, 'showAllParent'])->middleware('can:showAllParent');
    Route::get('admine/showDetailesParents', [AdmineAdmineParentController::class, 'showParentsDetailes'])->middleware('can:showParentsDetailes');
});


























































// Route::group(['middleware' => ['auth:sanctum']],function(){
    
//     Route::post('admine/insertImageProfile',[ImageProfileController::class,'insertImage'])->middleware('can:insertImage');
//     Route::post('admine/EdittImageProfile',[ImageProfileController::class,'editImage'])->middleware('can:editImage');
//     Route::Get('admine/showImageProfile',[ImageProfileController::class,'showImage'])->middleware('can:showImage');
//     Route::delete('admine/deleteImageProfile',[ImageProfileController::class,'deleteImage'])->middleware('can:deleteImage');
//  /*_________________________________________________________________________________________________________________*/
//     Route::post('admine/registerTeacher',[AdmineAdmineController::class, 'createAccountTeacher'])->middleware('can:createAccountTeacher');
//     Route::post('admine/editAccountTeacher',[AdmineAdmineController::class, 'editAccountTeacher'])->middleware('can:editAccountTeacher');
//     Route::get('admine/showAccountTeacher/{id}',[AdmineAdmineController::class, 'showAccountTeacher'])->middleware('can:showAccountTeacher');
//     Route::get('admine/sendEmailAccountTeacher/{id}',[AdmineAdmineController::class, 'sendEmailContentInformationTeacher']);
// /*_________________________________________________________________________________________________________________*/
//     Route::post('admine/registerParent',[AdmineAdmineParentController::class, 'createAccountParent'])->middleware('can:createAccountParent');
//     Route::post('admine/editAccountParent',[AdmineAdmineParentController::class, 'editAccountParent'])->middleware('can:editAccountParent');
//     Route::get('admine/showAccountParent/{id}',[AdmineAdmineParentController::class, 'showAccountParent'])->middleware('can:showAccountParent');
//     Route::get('admine/sendEmailAccountParent/{id}',[AdmineAdmineParentController::class, 'sendEmailContentInformationParent']);
// /*_________________________________________________________________________________________________________________*/
//     Route::post('admine/registerStudent',[AdmineAdmineStudentController::class, 'createAccountStudent'])->middleware('can:createAccountStudent');
//     Route::put('admine/editAccountStudent',[AdmineAdmineStudentController::class, 'editAccountStudent'])->middleware('can:editAccountStudent');
//     Route::get('admine/showAccountStudent/{id}',[AdmineAdmineStudentController::class, 'showAccountStudent'])->middleware('can:showAccountStudent');
//     Route::get('admine/sendEmailCodeStudent/{id}',[AdmineAdmineStudentController::class, 'sendEmailContentCode'])->middleware('can:sendEmailContentCode');
// /*________________________________________ClassRoom and ages_________________________________________________________________________*/ 
//     Route::get('admine/getAgeGroups',[AdmineAgeGroupController::class,'getAgeGroups'])->middleware('can:getAgeGroups');
//     Route::post('admine/createClass/{id}',[AdmineClassRoomController::class,'createClass']);/*->middleware('can:getAgeGroups');*/
//     Route::patch('admine/editClass',[AdmineClassRoomController::class,'editClass']);/*->middleware('can:getAgeGroups');*/
//     Route::get('admine/showClass/{id}',[AdmineClassRoomController::class,'showClass']);
//     Route::get('admine/showDetailsClass/{ageGroupId}/{ClassId}',[AdmineClassRoomController::class,'showDetailesClass']);
//     Route::post('admine/assignToClass',[AdmineTeacherAssignmentController::class,'assignTeacherToClass']);
//     Route::get('admine/getResponsibleTeacher/{id}',[AdmineTeacherAssignmentController::class,'getResponsibleTeachers']);
//     Route::post('admine/classrooms/add-student/{classroomId}', [AdmineStudentEnrollmentController::class,'addStudentToClass']);
//     Route::get('admine/showClassbyTeacher/{Teacherid}',[AdmineClassRoomController::class,'showClassbyTeacher']);

//  /*________________________________________Calendar and Lessons_________________________________________________________________________*/ 
//  Route::post('admine/createLesson', [AdmineLessonController::class,'createLesson']);
//  Route::put('admine/updateLesson/{lessonId}', [AdmineLessonController::class,'updateLesson']);
//  Route::delete('admine/deleteLesson/{lessonId}', [AdmineLessonController::class,'deleteLesson']);

//  Route::get('admine/showLessons', [AdmineLessonController::class,'showAllLessons']);
//  Route::get('admine/calendar/{classid}', [AdmineCalendarController::class ,'index']);
 
//  /*________________________________________Subjects Files_________________________________________________________________________*/ 
//  Route::post('admine/uploadFile',[AdmineSubjectController::class,'uploadSubject']);
//  Route::PUT('admine/updateSubject/{subjectId}',[AdmineSubjectController::class,'updateSubject']);
//  Route::delete('admine/deleteSubject/{subjectId}',[AdmineSubjectController::class,'deleteSubject']);
//  Route::get('admine/showAllSubjects/{ageGroup_id}',[AdmineSubjectController::class,'showAllSubjects']);
//  Route::get('admine/showSubjects',[AdmineSubjectController::class,'showSubjects']);

// /*________________________________________________________________________________________*/
// Route::post('admine/importStudent',[ExcelImportController::class,'importStudents']);
// Route::post('admine/importTeacher',[ExcelImportController::class,'importTeachers']);
// Route::post('admine/importParent',[ExcelImportController::class,'importParents']);
// /*________________________________________________________________________________________*/
// Route::post('admine/createPost',[PostController::class,'create']);
// Route::put('admine/updatePost/{postId}',[PostController::class,'update']);
// Route::delete('admine/deletePost/{postId}',[PostController::class,'delete']);
// //note :the comments and Likes in api route 
   

//    //ويبقى التقارير و فرز الطلاب حسب تقييمهم,notification

// /*_______________________________________Search__________________________________________________________________________*/ 
// Route::get('admine/searchByName/{name}',[AdmineTeacherSearchSortController::class,'searchByName']);
// Route::get('admine/searchByEmail/{email}',[AdmineTeacherSearchSortController::class,'searchByEmail']);
// Route::get('admine/searchByPhone/{phone}',[AdmineTeacherSearchSortController::class,'searchByPhone']);
// /*______________________________________Sort____________________________________________________________*/
// Route::get('admine/sortAcs/{id?}',[AdmineTeacherSearchSortController::class,'sortAcs']);
// Route::get('admine/sortDesc/{id?}',[AdmineTeacherSearchSortController::class,'sortDesc']);
// /*______________________________________Show All Users____________________________________________________________*/
// Route::get('admine/showAllTeachers',[AdmineAdmineController::class,'showAllTeacher']);
// Route::get('admine/showDetailesTeachers',[AdmineAdmineController::class,'showTeacherDetailes']);

// Route::get('admine/showAllStudents',[AdmineAdmineStudentController::class,'showAllStudent']);
// Route::get('admine/showDetailesStudents',[AdmineAdmineStudentController::class,'showStudentDetailes']);

// Route::get('admine/showAllParents',[AdmineAdmineParentController::class,'showAllParent']);
// Route::get('admine/showDetailesParents',[AdmineAdmineParentController::class,'showParentsDetailes']);
// Route::post('admine/registerTeacher', [AdmineAdmineController::class, 'createAccountTeacher'])
// ->middleware('auth:sanctum')
//});





// Route::get('admine/test', function() {
    
//     // Gdrive::put('C:\Users\Laptop Syria\Downloads\Tinytots\DataBase', $request->file('file'));
//      // // or
//      // $folderId='1CeWQoGeKtXsHuVq60uGip9EmivA-C7aq';
//       Gdrive::put('sub_3.jpg', public_path('images/profile/admine/665cb41154ef7.jpg'));
//          return response('Image Uploaded!', 200);
 
//      });
//  Route::get('admine/test', function() {
//     Storage::disk('google')->put('test.txt', 'Hello World');
// });
