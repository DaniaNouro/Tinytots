<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\AgeGroup;
use App\Models\ClassRoomTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoom extends Model
{
    use HasFactory;


    protected $fillable=['class_name','capacity','ageGroup_id'];
 
   protected $hidden=['updated_at','created_at','class_room_teacher','pivot'];
   
        public function ageGroup()
        {
            return $this->belongsTo(AgeGroup::class);
        }

        public function students(){
            return $this->belongsToMany(Student::class, 'class_room_student', 'student_id','class_room_id', );
        }
        public function teachers(){
        return $this->belongsToMany(Teacher::class, 'class_room_teacher', 'class_room_id', 'teacher_id');
        }

        public function attendances(){

            return $this->hasMany(Attendance::class,'classroom_id');
         }
     
         public function homeworks(){
     
           return $this->hasMany(HomeWork::class,'classroom_id');
         }
    }



