<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Evaluation_Student;
use App\Models\EvaluationNeedworkpoint;
use App\Models\EvaluationPositivepoint;
use App\Models\NeedWork;
use App\Models\Positive_Point;
use App\Models\Student;
use App\Models\ClassRoomStudent;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherEvaluationService{

    public function StudentEvaluation($request){
        if (Auth::user()->hasRole('teacher')){
            $user = Auth::user()->id;
            $teacher = Teacher::where('user_id', $user)->first()->id;
            
            $teachertFirstName=Teacher::where('user_id', $user)->first()->first_name;
            $teacheLasttName=Teacher::where('user_id', $user)->first()->last_name;
            $student=Student::find($request['student_id']);  
            $date=$request['date'];
            $type=$request['type'];

            
            if ($type == 1) { 
                $psitivePointID = Positive_Point::find($request['positivepoint_id']);
                
            $evaluation = Evaluation::create([
                'date' => $date
            ]);
                            
            $evaluation->students()->attach($student->id);
            $evaluation->teachers()->attach($teacher);
                $evaluation->positivePoints()->attach($psitivePointID->id,['created_at' => $date]); 
                return [ 'data' => [
                    'evaluation_id' => $evaluation->id,
                    'teacher_id' =>$teacher,
                    'teacher_name' =>$teachertFirstName." ".$teacheLasttName,
                    'student-id' =>$student->id,
                    'student_name' =>$student->first_name." ".$student->last_name,
                    'PositivePoint_id' =>$psitivePointID->id,
                    'positivePoint_name' =>$psitivePointID->name,
                    'positivePoint_value' =>$psitivePointID->value,

                ],'message' => 'Student Has Evaluated Successfully'];

            }
            elseif ($type == 0) {
                $notes=$request['notes'];
                $needworkPointID = NeedWork::find($request['needwork_id']);
                
                $evaluation = Evaluation::create([
                'date' => $date
                 ]);
                                 
            $evaluation->students()->attach($student->id);
            $evaluation->teachers()->attach($teacher);
                $evaluation->needworkPoints()->attach($needworkPointID->id,['notes' => $notes,'created_at' => $date]);
                return [ 'data' => [
                    'evaluation_id' => $evaluation->id,
                    'teacher_id' =>$teacher,
                    'teacher_name' =>$teachertFirstName." ".$teacheLasttName,
                    'student-id' =>$student->id,
                    'student_name' =>$student->first_name." ".$student->last_name,
                    'NeedworkPoint_id' =>$needworkPointID->id,
                    'NeedworkPoint_name' =>$needworkPointID->name,
                    'NeedworkPoint_value' =>$needworkPointID->value,
                    'notes' => $notes
                ],'message' => 'Student Has Evaluated Successfully'];
                
            $evaluation->students()->attach($student->id);
            $evaluation->teachers()->attach($teacher);
            }
    }else {
            return ['data' => [], 'message' => "you are not allowed to do this"];
        }
    }
//................................................................................................................................
public function RandomEvaluation($classID)
{
    if (Auth::user()->hasRole('teacher')) {
        $student = ClassRoomStudent::where('class_room_id', $classID) 
            ->inRandomOrder()
            ->first();

        if ($student) {
            $studentId = $student->student_id;
            $studentInfo = Student::find($studentId);

            $studentInformation[] = [
                'student_id' => $studentId,
                'student_name' => $studentInfo->first_name . " " . $studentInfo->last_name
            ];

            return ['data' => $studentInformation, 'message' => 'A student was successfully randomly selected'];
        } else {
            return ['data' => [], 'message' => 'There are no students in this class'];
        }
    } else {
        return ['data' => [], 'message' => 'You are not authorized to perform this action'];
    }
}
//................................................................................................................................
public function ReturnStudentEvaluations($studentId)
{
    $student = Student::find($studentId);
    if (!$student) {
        return response()->json([
            'message' => 'student not found',
        ], 422);
    }
    $studentEvaluation = Evaluation_Student::where('student_id', $studentId)->pluck('evaluation_id');
    
    $positivePoints = EvaluationPositivepoint::whereIn('evaluation_id', $studentEvaluation)
        ->join('positive__points', 'positive__points.id', '=', 'evaluation_positivepoints.positivePoint_id')
        ->join('evaluations','evaluations.id', '=' ,'evaluation_positivepoints.evaluation_id')
        ->select('positive__points.id', 'positive__points.name', 'positive__points.value', 'evaluations.date') 
        ->get();
        
    $needWorkPoints = EvaluationNeedworkpoint::whereIn('evaluation_id', $studentEvaluation)
        ->join('need_works', 'need_works.id', '=', 'evaluation_needworkpoints.needworkPoint_id')
        ->join('evaluations','evaluations.id', '=' ,'evaluation_needworkpoints.evaluation_id')
        ->select('need_works.id', 'need_works.name', 'need_works.value', 'evaluation_needworkpoints.notes', 'evaluations.date') 
        ->get();

    return [
        'data' => [
            'positive_points' => $positivePoints,
            'need_work_points' => $needWorkPoints
        ],
        'message' => 'These are all the points the student received.'
    ];
}


//..........................................................................................................................................
public function ReturnStudentEvaluationInSpecificPeriod($request)
{
    $studentID=$request['student_id'];
    $start_date = $request['start_date'];
    $end_date = $request['end_date'];
    
    $studentEvaluation = Evaluation_Student::where('student_id', $studentID)->pluck('evaluation_id');
    $evaluationIDs = Evaluation::whereIn('id', $studentEvaluation)
        ->whereBetween('date', [$start_date, $end_date])
        ->pluck('id');
        
    $positivePoints = EvaluationPositivepoint::whereIn('evaluation_id', $evaluationIDs)
        ->join('positive__points', 'positive__points.id', '=', 'evaluation_positivepoints.positivePoint_id')
        ->join('evaluations','evaluations.id', '=' ,'evaluation_positivepoints.evaluation_id')
        ->select('positive__points.id', 'positive__points.name', 'positive__points.value','evaluations.date')
        ->get();
        
    $needWorkPoints = EvaluationNeedworkpoint::whereIn('evaluation_id', $evaluationIDs)
        ->join('need_works', 'need_works.id', '=', 'evaluation_needworkpoints.needworkPoint_id')
        ->join('evaluations','evaluations.id', '=' ,'evaluation_needworkpoints.evaluation_id')
        ->select('need_works.id', 'need_works.name', 'need_works.value','evaluation_needworkpoints.notes','evaluations.date')
        ->get();

    return [
        'data' => [
            'positive_points' => $positivePoints,
            'need_work_points' => $needWorkPoints
        ],
        'message' => 'These are all the points the student received between the specified dates'
    ];
}
//.....................................................................................................................
public function GivingStudentsPositivePoints($request){
    if (Auth::user()->hasRole('teacher')){
    $NameOfPoint=$request['name'];
    $ValueOfPoint=$request['value'];

    $PositivePointData=Positive_Point::create([
    'name' => $NameOfPoint,
    'value' => $ValueOfPoint
    ]);
    $pointId = $PositivePointData->id;
    return [ 'data' => [
        'Point_id' =>$pointId,
        'name' => $NameOfPoint,
        'value' => $ValueOfPoint
    ] ,'message' => 'Point Awarded Successfully'];
}else {
    return ['data' => [], 'message' => "you are not allowed to do this"];
}
}
//.............................................................................................................................
public function GivingStudentsNeedworkPoints($request){
    if (Auth::user()->hasRole('teacher')){
 
         $NameOfPoint=$request['name'];
        $ValueOfPoint=$request['value'];

        $NeedworkPointData=NeedWork::create([
        'name' => $NameOfPoint,
        'value' => $ValueOfPoint,
          ]);
          $needworkPoint=$NeedworkPointData->id;
    
       return [ 'data' => [
        'Point_id' =>$needworkPoint,
        'name' => $NameOfPoint,
        'value' => $ValueOfPoint
       ],'message' => 'Point Awarded Successfully'];

        
    }else {
        return ['data' => [], 'message' => "you are not allowed to do this"];
    }
}
//............................................................................................................................................
public function UpdatePositivePoint($request){
    if (Auth::user()->hasRole('teacher')){
        $point=Positive_Point::Find($request['id']);
        $updateData = [];
        if($request->has('name')){
            $newName=$request['name'];
            $updateData['name']=$newName;
        }
        if($request->has('value')){
            $newValue=$request['value'];
            $updateData['value']=$newValue;
        }
        $point->update($updateData);
        return [ 'data' => $updateData , 'message' => 'The point has been successfully modified.'];

  
     } else{
        return ['data' => [], 'message' => "you are not allowed to do this"];
    }
}
//.........................................................................................................................
public function UpdateNeedworkPoint($request){
    if (Auth::user()->hasRole('teacher')){
        $point=NeedWork::Find($request['id']);
        $updateData = [];
        if($point){
        if($request->has('name')){
            $newName=$request['name'];
            $updateData['name']=$newName;
        }
        if($request->has('value')){
            $newValue=$request['value'];
            $updateData['value']=$newValue;
        }
        $point->update($updateData);
        return [ 'data' => $updateData , 'message' => 'The point has been successfully modified.'];

    }else{
        return [ 'data' => [], 'message' => 'The Point Not Found'];
    }
     } else{
        return ['data' => [], 'message' => "you are not allowed to do this"];
    }
}
//.........................................................................................................................
public function DelatePositivePoint($pointid){
    $point=Positive_Point::findOrFail($pointid);
    $point->delete();
    return [ 'date' => $point,'message' => 'Point Has Deleted Successfully'];
}
//..............................................................................................................
public function DelateNeedWorkPoint($pointid){
    $point=NeedWork::findOrFail($pointid);
    $point->delete();
    return [ 'date' => $point,'message' => 'Point Has Deleted Successfully'];
}
//...................................................................................................
public function ReturnPointsOfStudent($studentId)
{
    $student = Student::find($studentId);
    if (!$student) {
        return response()->json([
            'message' => 'student not found',
        ], 422);
    }
    $studentEvaluation = Evaluation_Student::where('student_id', $studentId)->pluck('evaluation_id');
    
    $positivePoints = EvaluationPositivepoint::whereIn('evaluation_id', $studentEvaluation)
        ->join('positive__points', 'positive__points.id', '=', 'evaluation_positivepoints.positivePoint_id')
        ->select('positive__points.value')
        ->sum('positive__points.value');
        
    $needWorkPoints = EvaluationNeedworkpoint::whereIn('evaluation_id', $studentEvaluation)
        ->join('need_works', 'need_works.id', '=', 'evaluation_needworkpoints.needworkPoint_id')
        ->select('need_works.value')
        ->sum('need_works.value');

    $totalPoints = $positivePoints + $needWorkPoints;

    return $totalPoints;
}
//.........................................................................................................
public function ReturnAllPoint($request){
    $type=$request['type'];
    if($type==0){
        $needwork=NeedWork::get();
        return ['data' =>$needwork,'message' => 'All Needwok Point'];
    } 
    elseif($type==1){
        $positivepoint=Positive_Point::get();
        return ['data' =>$positivepoint ,'message' =>'All Positive Point'];
    }
}
//..............................................................................................................
public function count_points($request) {
    $studentId = $request['student_id'];
    $type = $request['type'];

    $student = Student::find($studentId);

    if ($type == 1) {
        $evaluations = Evaluation_Student::where('student_id', $studentId)->pluck('evaluation_id');

        $positivePointsCount = DB::table('positive__points')
            ->leftJoinSub(
                DB::table('evaluation_positivepoints')
                    ->join('evaluations', 'evaluation_positivepoints.evaluation_id', '=', 'evaluations.id')
                    ->whereIn('evaluations.id', $evaluations)
                    ->select('evaluation_positivepoints.positivePoint_id', 'evaluation_positivepoints.evaluation_id'), 
                'evaluation_positivepoints_filtered', 'positive__points.id', '=', 'evaluation_positivepoints_filtered.positivePoint_id'
            )
            ->select('positive__points.id as positivePoint_id', DB::raw('count(evaluation_positivepoints_filtered.evaluation_id) as count'))
            ->groupBy('positive__points.id')
            ->get();

        return [
            'positive_points_count' => $positivePointsCount
        ];
    }
        elseif ($type == 0) {
            $evaluations = Evaluation_Student::where('student_id', $studentId)->pluck('evaluation_id');
    
            $needWorkPointsCount = DB::table('need_works')
                ->leftJoinSub(
                    DB::table('evaluation_needworkpoints')
                        ->join('evaluations', 'evaluation_needworkpoints.evaluation_id', '=', 'evaluations.id')
                        ->whereIn('evaluations.id', $evaluations) // Filter evaluations first
                        ->select('evaluation_needworkpoints.needworkPoint_id', 'evaluation_needworkpoints.evaluation_id'), 
                    'evaluation_needworkpoints_filtered', 'need_works.id', '=', 'evaluation_needworkpoints_filtered.needworkPoint_id'
                )
                ->select('need_works.id as needworkPoint_id', DB::raw('count(evaluation_needworkpoints_filtered.evaluation_id) as count')) // Remove 'notes'
                ->groupBy('need_works.id')
                ->get();
    
            return [
                'need_work_points_count' => $needWorkPointsCount 
            ];
    }
}
}
