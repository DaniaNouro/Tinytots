<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Group_student;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherGroupService{

    public function createGroup($request){
        if (Auth::user()->hasRole('teacher')) {
            $user = Auth::user()->id;
            $teacher = Teacher::where('user_id', $user)->first()->id;
            
                $group = Group::create([
                    'group_name' => $request['group_name'],
                    'teacher_id' => $teacher,
                    'class_id' => $request['class_id'],
                    'task_id'=>$request['task_id']
                ]);
    
                if (isset($request['students']) && is_array($request['students'])) {
                    foreach ($request['students'] as $student) {
                        $studentId = $student['id'];
                        $group->students()->attach($studentId);
                    }
                }
    
                $task = Task::find($request['task_id']);
    
                 $task->groupStudents()->attach($group->id);
    
               $studentsNames = $group->students->pluck('first_name');
               return [ 'data' => 
               [
                'students' => $studentsNames,
                'task' => $task->first(),
               ], 'message' => 'Group Was Created Successfully'
            ];

        
    }else {
            return ['data' => [], 'message' => "you are not allowed to do this"];
        }
    
        }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function UpdateStudent($request) {
    if (Auth::user()->hasRole('teacher')) {
        $group = Group::find($request['group_id']);
        $type = $request['type'];

        if ($type == 1) { 
            // Add students to the group
            $studentsToAdd = $request['students'] ?? []; 
            $existingStudents = [];

            if (!empty($studentsToAdd)) {
                foreach ($studentsToAdd as $studentId) {
                    if (!$group->students->contains($studentId)) {
                        $group->students()->attach($studentId);
                    } else {
                        $existingStudents[] = $group->students()->wherePivot('student_id', $studentId)->first()->first_name;
                    }
                }

                $studentsData = $group->students()->get();
                $studentsList = $studentsData->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->first_name . ' ' . $student->last_name
                    ];
                });
                $message = 'Students were added to the group successfully';
                
                if (!empty($existingStudents)) {
                    abort(404, 'The following students are already in the group: ' . implode(', ', $existingStudents));
                }

                return ['data' => $studentsList, 'message' => $message];
            } else {
                return ['data' => [], 'message' => 'No students were provided for addition.'];
            }
        } elseif ($type == 0) {
            $studentsToDelete = $request['students'] ?? [];

            if (!empty($studentsToDelete)) {
                foreach ($studentsToDelete as $studentId) {
                    $group->students()->detach($studentId);
                }

                $studentsData = $group->students()->get();
                $studentsList = $studentsData->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->first_name . ' ' . $student->last_name
                    ];
                });
                $message = 'Students were removed from the group successfully';

                return ['data' => $studentsList, 'message' => $message];
            } else {
                return ['data' => [], 'message' => 'No students were provided for removal.'];
            }
        }
    } else {
        return ['data' => [], 'message' => 'You are not allowed to do this'];
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function ReturnSDetailsOfGroup($groupid)
{
    if (Auth::user()->hasRole('teacher')) {
        $students = DB::table('group_students')
            ->join('students', 'group_students.student_id', '=', 'students.id')
            ->where('group_students.group_id', $groupid)
            ->select('students.first_name', 'students.last_name', 'students.id as student_id') 
            ->get();

        $task = DB::table('group_student_tasks')
            ->join('tasks', 'group_student_tasks.task_id', '=', 'tasks.id')
            ->where('group_student_tasks.group_student_id', $groupid)
            ->first();

        $formattedStudents = $students->map(function ($student) {
            return [
                'student_id' => $student->student_id,
                'student_name' => $student->first_name . ' ' . $student->last_name
            ];
        });

        return [
            'data' => [
                'group_id' => $groupid,
                'students' => $formattedStudents,
                'task' => [
                    'task_id' => $task->id,
                    'task_name' =>$task->task_name
             ],
            ],
            'message' => 'Details Of Group'
        ];

    } else {
        return ['data' => [], 'message' => "you are not allowed to do this"];
    }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function UpdateGroup($request)
{
    if (Auth::user()->hasRole('teacher')) {
        $group = Group::find($request['group_id']);
        $updatedGroup = [];

        if (isset($request['group_name'])) {
            $group->group_name = $request['group_name'];
            $updatedGroup['New Group Name'] = $group->group_name;
        }

        if (isset($request['task_id'])) {
            $task = Task::find($request['task_id']);
            if (!$task) {
                return ['data' => [], 'message' => 'Invalid task ID.'];
            }
            $group->task_id = $request['task_id'];
            $updatedGroup['New Task ID'] = $group->task_id;
            $updatedGroup['New Task Name'] = $task->task_name;
        }

        $group->save();

        return ['data' => $updatedGroup, 'message' => 'Group or Task Name Was Updated Successfully'];
    } else {
        return ['data' => [], 'message' => "you are not allowed to do this"];
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function DelateGroup($groupID){
    $group=Group::findorFail($groupID);
    $group->delete();
    return [ 'date' => $group,'message' => 'Group Has Deleted Successfully'];

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function ReturnAllGroupsToThisClass($classID)
{
    $groups = Group::where('class_id', $classID)
        ->get();

    $groupData = [];

    foreach ($groups as $group) {
        $groupData[] = [
            'group_id' => $group->id, 
            'group_name' => $group->group_name,
        ];
    }

    return ['data' => $groupData, 'message' => 'All Groups In This Class'];
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function ReturnAllTasks(){
    $tasks=Task::get();
    return ['data' => $tasks ,'message' => 'All Tasks'];
}

}