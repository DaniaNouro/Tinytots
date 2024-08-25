<?php

namespace App\Services;
use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class StudentEnrollmentService
{
    /**
     * Add students to a class if seats are available.
     *
     * @param array $request The request data containing student IDs.
     * @param int $classID The ID of the class to add students to.
     * @return array The result of the addition operation.
     */
    public function addStudentToClass($request)
    {   
        $classID=$request['class_id'];
        $class = ClassRoom::findOrFail($classID);
        $students = $request['students'];
        $countStudentsToBeAdded = count($students);

        $currentStudentCount = $class->students()->count();
        $availableSeats = $class->capacity - $currentStudentCount;

        if ($countStudentsToBeAdded <= $availableSeats) {
            $result = $this->addStudents($students, $class);
            if (!empty($result['not_found']) && (!empty($result['existing']))) {
                
                $message = "Some students were added, but some were already in the class.";
            } elseif (!empty($result['not_found'])) {
               
                $message = "Some students were not found and could not be added.";
            } elseif (!empty($result['existing'])) {
                
                $message = "All students are already in the class.";
            } else {
               
                $message = "All students added successfully.";
            }
            
            return [
                'notFoundStudent'=>$result['not_found'],
                'students_added' => $result['added'],
                'students_existing' => $result['existing'], // توضيح إذا كان الطالب موجود بالفعل
                'message'=>$message];
            
        }

        return ['students_added' => [], 'message' => "The classroom is full, unable to add more students."];
    }
/*_____________________________________________________________________________________________*/
    /**
     * Add an array of students to a class.
     *
     * @param array $studentIDs IDs of the students to add.
     * @param ClassRoom $class The class to add students to.
     * @return array The IDs of added and not found students.
     */
    public function addStudents(array $studentIDs, ClassRoom $class)
    {
        $addedStudents = [];
        $notFoundStudents = [];
        $existingStudents = []; //existing students
    
        foreach ($studentIDs as $studentID) {
            $student = Student::find($studentID);
            if ($student) {
                if (!$class->students()->where('student_id', $studentID)->exists()) {
                    try {
                        $class->students()->syncWithoutDetaching([$studentID]);
                        $addedStudents[] = $studentID;
                    } catch (ModelNotFoundException $e) {
                     
                        $notFoundStudents[] = $studentID;
                    }
                } else {
                    $existingStudents[] = $studentID; 
                }
            } else {
                $notFoundStudents[] = $studentID;
            }
        }
    
        return [
            'added' => $addedStudents,
            'not_found' => $notFoundStudents,
            'existing' => $existingStudents 
        ];
    }
    
}