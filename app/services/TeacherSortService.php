<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\NeedWork;
use App\Models\Positive_Point;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class TeacherSortService{


    public function SortByAlphabet($classroom) {
        if (Auth::user()->hasRole('teacher')) {
            $classroom = Classroom::find($classroom);
            if (!$classroom) {
                return response()->json([
                    'message' => 'class_room not found',
                ], 422);
            }
            $students = $classroom->students()->with('user')->get();
            $studentsData = [];
            foreach ($students as $student) {
                $SumPointService = new TeacherEvaluationService();
                $sumPointsData = $SumPointService->ReturnPointsOfStudent($student->id);
                $studentsData[] = [
                    'student_id' => $student->id,
                    'student_name' => $student->first_name . " " . $student->last_name,
                    'points' => $sumPointsData,
                    'profile_picture' => $student->user->image
                ];
            }
    
            $studentsData = collect($studentsData);
    
            $sortedStudentsData = $studentsData->sortBy('student_name'); 
    
            return ['data' => $sortedStudentsData, 'message' => 'All students in this class sorted alphabetically'];
        } else {
            return ['data' => [], 'message' => "you are not allowed to do this"];
        }
    }
    
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        public function SortByHighestPoints($classroom) {
            if (Auth::user()->hasRole('teacher')) {
                $classroom = Classroom::find($classroom);
                if (!$classroom) {
                    return response()->json([
                        'message' => 'class_room not found',
                    ], 422);
                }
                $students = $classroom->students;
                $studentsWithPoints = [];
                foreach ($students as $student) {
                    $SumPointService = new TeacherEvaluationService();
                    $sumPointsData = $SumPointService->ReturnPointsOfStudent($student->id);
                    $studentsWithPoints[] = [
                         'student_id' => $student->id,
                        'student_name' => $student->first_name." ".$student->last_name,
                        'points' => $sumPointsData,
                        'profile_picture' => $student->user->image
                    ];
                }
                $sortedStudentsWithPoints = collect($studentsWithPoints)->sortByDesc('points')->values()->all();
                return ['data' => $sortedStudentsWithPoints , 'message' => 'Student Sorted By High Points'];
        
            } else {
                return ['data' => [], 'message' => "you are not allowed to do this"];
            }
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function SortByLowestPoints($classroom) {
        if (Auth::user()->hasRole('teacher')) {
            $classroom = Classroom::find($classroom);
            if (!$classroom) {
                return response()->json([
                    'message' => 'class_room not found',
                ], 422);
            }
            $students = $classroom->students;
            $studentsWithPoints = [];
            foreach ($students as $student) {
                $SumPointService = new TeacherEvaluationService();
                $sumPointsData = $SumPointService->ReturnPointsOfStudent($student->id);
                $studentsWithPoints[] = [
                    'student_id' => $student->id,
                    'student_name' => $student->first_name." ".$student->last_name,
                    'points' => $sumPointsData,
                    'profile_picture' => $student->user->image
                ];
            }
            $sortedStudentsWithPoints = collect($studentsWithPoints)->sortBy('points')->values()->all();
            return ['data' => $sortedStudentsWithPoints , 'message' => 'Students Sorted By Lowest Points'];
    
        } else {
            return ['data' => [], 'message' => "you are not allowed to do this"];
        }
    }
    }