<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admine;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionsSeeder extends Seeder
{
    
    

    public function run()
    { 
     

        //create Roles
      $admineRole=Role::create(['name'=>'admine']);
      $teacherRole=Role::create(['name'=>'teacher']);
      $studentRole=Role::create(['name'=>'student']);
      $parentRole=Role::create(['name'=>'parent']);

      $adminePermissions = [
        'insertImage',
        'editImage',
        'showImage',
        'deleteImage',
        'createAccountTeacher',
        'editAccountTeacher',
        'showAccountTeacher',
        'sendEmailContentInformationTeacher',
        'createAccountParent',
        'editAccountParent',
        'showAccountParent',
        'sendEmailContentInformationParent',
        'createAccountStudent',
        'editAccountStudent',
        'showAccountStudent',
        'sendEmailContentCode',
        'getAgeGroups',
        'createClass',
        'editClass',
        'showClass',
        'showDetailesClass',
        'assignTeacherToClass',
        'getResponsibleTeachers',
        'addStudentToClass',
        'showClassbyTeacher',
        'createLesson',
        'updateLesson',
        'deleteLesson',
        'showAllLessons',
        'index',
        'uploadSubject',
        'updateSubject',
        'deleteSubject',
        'showAllSubjects',
        'showSubjects',
        'importStudents',
        'importTeachers',
        'importParents',
        'createPost',
        'updatePost',
        'deletePost',
        'searchByName',
        'searchByEmail',
        'searchByPhone',
        'sortAcs',
        'sortDesc',
        'showAllTeacher',
        'showTeacherDetailes',
        'showAllStudent',
        'showStudentDetailes',
        'showAllParent',
        'showParentsDetailes'
    ];

    $teacherPermissions = [
      'takeAttendance',
      'takeMultipleAttendance',
      'AttendanceOnASpecificDate',
      'AttendanceRecordForTheStudent',
      'StudentEvaluation',
      'RandomEvaluation',
      'ReturnStudentEvaluations',
      'ReturnStudentEvaluationInSpecificPeriod',
      'ReturnStudentsEvaluationsInSpecificDay',
      'GivingStudentsPositivePoints',
      'GivingStudentsNeedworkPonints',
      'UpdatePositivePoint',
      'UpdateNeedworkPoint',
      'ReturnStudentPointsInSpecificPeriod',
      'ReturnStudentsPointsInSpecificDay',
      'ReturnPointsOfStudent',
      'SortByAlphabet',
      'SortByHighestPoints',
      'SortByLowestPoints',
      'createGroup',
      'UpdateStudent',
      'ReturnGroupsOfStudent',
      'ReturnSDetailsOfGroup',
      'UpdateGroup',
      'uploadHomework',
      'updateHomework',
      'deleteHomework',
      'NumOfStudentHasUploadedHomework',
      'showClassForTeacher',
      'getAgeGroups',
      'get_calendar_data',
      'showAllSubjects',
      'createPost',
      'updatePost',
      'deletePost',
      'addComment',
      'deleteComment',
      'updateComment',
      'showComments',
      'getCommentCount',
      'like',
      'unLike',
      'getCountLikes',
      'showPosts',
      'ReturnAllTasks',
      'count_points',
      'getStudentsDataWithPoints',
      'getStudentsWithHomework',
      'getStudentsThatNotUploadedHomework',
      'CreateReports'
    ];

    $studentPermissions = [
      'ReturnAttendanceOfStudent',
      'ReturnDateEvaluation',
      'EvaluationStudentInSpecificDate',
      'SumPointOfStudent',
      'uploadHomeworkstudent',
      'updateHomeworkstudent',
      'deleteHomeworkStudent',
      'ReturnAllHomeworkToThisClass',
      'Returnhomeworkstudentthatuploaded',
      'insertImage',
      'profileStudent',
      'showSubjects'
  ];

    $parentPermissions = [
      'showChildrens',
      'showCountChildrens',
      'showProfile',
      'showTimeTable',
      'showPosts',
      'get-calendar-data',
      'showSubjects',
      'insertImageProfile',
      'EditImageProfile',
      'showImageProfile',
      'deleteImageProfile',
      'showEvaluations',
      'showAttendance'
  ];

    // Create and assign permissions
    $allPermissions = array_merge($adminePermissions, $teacherPermissions, $studentPermissions, $parentPermissions);

    foreach ($allPermissions as $permission) {
        Permission::findOrCreate($permission, 'web');
    }

    // Assign permissions to roles
    $admineRole->syncPermissions($adminePermissions);
    $teacherRole->syncPermissions($teacherPermissions);
    $studentRole->syncPermissions($studentPermissions);
    $parentRole->syncPermissions($parentPermissions);
}












       
      // //Define all permission with array
      // $Permissions = 
      // ['createAccountTeacher',
      //  'createAccountStudent',
      //  'createAccountParent',
      //  'editAccountTeacher',
      //  'editAccountStudent',
      //  'editAccountParent',
      //  'showAccountTeacher',
      //  'showAccountStudent',
      //  'showAccountParent',
      //  'deleteAccountTeacher',
      //  'deleteAccountStudent',
      //  'deleteAccountParent', 
      //  'createClasses',
      //  'SearchTeacher',
      //  'searchStudent',
      //  'seachParent',
      //  'insertImage',
      //  'editImage',
      //  'showImage',
      //  'deleteImage',
      //  'sendEmailContentInformationTeacher',
      //   'sendEmailContentInformationParent',
      //   'sendEmailContentCode',
      //   'takeAttendance'      ];

      // foreach ($Permissions as $PermissionName) {
      //   Permission::findOrCreate($PermissionName, 'web');
      // }
      
      //   //Assign permission to roles we have tow instruction
      //   $admineRole->syncPermissions($Permissions);//delete old permissins and keep those inside the $Permissions
      //   $teacherRole->givePermissionTo(['takeAttendance']);//add permission on top of olds ones *note the input is assray
         
        ////////////////////////////////////////////////////////////
        //create user and assighn permission


        // $admineUser=User::factory()->create([
        //   'email'=>'admineuser@gmail.com',
        //   'password'=>bcrypt('password')
        // ]);



        // $admineUser=Admine::factory()->create([
        //   'user_id'=>1,
        //   'first_name'=>'Dania',
        //   'last_name'=>'Nouro'
        // ]);



      //  $admineUser->assignRole(('admine'));






        //get permission name as array /we user pluk() to determinate column 

        /*
        $Permissions=$admineRole->permissions()->pluck('name')->toArray();//we put toarrray because givepermissinto input (array)
        $admineUser->givePermissionTo($Permissions);*/

        //same to teacher
        /*
        $teacherUser=User::factory()->create([
            'email'=>'teacheruser@gmail.com',
            'password'=>bcrypt('password')
          ]);
          */
       
  
        //  $teacherUser->assignRole(('teacher'));
          //get permission name as array /we user pluk() to determinate column 
          // $Permissions=$teacherRole->permissions()->pluck('name')->toArray();//we put toarrray because givepermissinto input (array)
          // $teacherUser->givePermissionTo($Permissions);
  
          //when we finish we should to add to DataBase Seeder
    }

