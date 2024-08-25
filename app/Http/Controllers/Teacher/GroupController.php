<?php

namespace App\Http\Controllers\Teacher;

use Throwable;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Responces\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\TeacherGroupService;
use App\Http\Requests\StudentGroupRequest;
use App\Http\Requests\StudentOfGroupRequest;
use App\Http\Requests\GroupNameUpdateRequest;
use App\Http\Requests\StudentAddToGroupRequest;
use App\Http\Requests\StudentEvaluationsRequest;
use App\Http\Requests\StudentRemoveFromGroupRequest;

class GroupController extends Controller
{
    protected $teacherGroupService;
    public function __construct(TeacherGroupService $teacherGroupService)
    {
        $this->teacherGroupService = $teacherGroupService;   
    }
    
    public function createGroup(StudentGroupRequest $request){
        try {
            $validatedData = $request->validated();
            return $result = $this->teacherGroupService->createGroup($validatedData);
            return Response::Success($result['data'], $result['message']);
           
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        } 

    }

//............................................................................................................................
public function UpdateStudent(StudentAddToGroupRequest $request): JsonResponse {
    try {
        $validatedData = $request->validated();
        $result = $this->teacherGroupService->UpdateStudent($validatedData);

        if ($result['data']) {
            return Response::Success($result['data'], $result['message']);
        } else {
            return Response::Error([], 'Student already exists in this group', 422);
        }
       
    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message, 422);
    } 
}
//...............................................................................................................................

//....................................................................................................................................
// public function ReturnGroupsOfStudent(StudentEvaluationsRequest $request ): JsonResponse{
//     try {
//         $validatedData = $request->validated();
//         $result = $this->teacherGroupService->ReturnGroupsOfStudent($validatedData);
//         return Response::Success($result['data'], $result['message']);
       
//     } catch (Throwable $th) {
//         $message = $th->getMessage();
//         return Response::Error([], $message);
//     } 
// }
//..............................................................................................................................................
public function ReturnSDetailsOfGroup($groupid){
    $data=$this->teacherGroupService->ReturnSDetailsOfGroup($groupid);
    return $data;
}
//.........................................................................................................................
public function UpdateGroup(GroupNameUpdateRequest $request): JsonResponse{
    try {
        $validatedData = $request->validated();
        $result = $this->teacherGroupService->UpdateGroup($validatedData);
        return Response::Success($result['data'], $result['message']);
       
    } catch (Throwable $th) {
        $message = $th->getMessage();
        return Response::Error([], $message);
    } 
}
/////////////////////////////////////////////////////////////////////////
public function DelateGroup($groupID){
    $data=$this->teacherGroupService->DelateGroup($groupID);
    return $data;
}
////////////////////////////////////////////////////////////////////////
public function getTasks()
{
    $tasks = Task::all(); // Get all tasks from the database
    
    $formattedTasks = []; // Initialize an array to store formatted tasks

    foreach ($tasks as $task) {
        $formattedTasks[] = [
            'id' => $task->id, // Task ID
            'task_name' => $task->task_name // Task name
        ];
    }

    return $formattedTasks; // Return the array of formatted tasks
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function ReturnAllGroupsToThisClass($classID){
    $data=$this->teacherGroupService->ReturnAllGroupsToThisClass($classID);
    return $data;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function ReturnAllTasks(){
    $data=$this->teacherGroupService->ReturnAllTasks();
    return $data;
}
}
