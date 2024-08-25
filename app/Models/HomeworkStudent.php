<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkStudent extends Model
{
    use HasFactory;
    protected $fillable=['homework_id','uploaded_homework'];
    protected $hidden = ['created_at', 'updated_at'];
////Relations
     
    public function homework(){

        return $this->belongsTo(home_work::class,'homework_id');
    }


    public function students(){

        return $this->belongsToMany(Student::class,'homeworkstudent_relationships','student_id','homeworkstudent_id');
    }
}
