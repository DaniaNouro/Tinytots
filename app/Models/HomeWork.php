<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeWork extends Model
{
    use HasFactory;
    protected $fillable=['homework_name','homework_path','classroom_id','teacher_id'];
    protected $hidden=['created_at','updated_at'];
////Relation
    public function classroom(){

        return $this->belongsTo(Classroom::class,'classroom_id');
    }
    public function teacher(){

        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function homeworkstudents(){

        return $this->hasMany(HomeworkStudent::class,'homework_id');
    }


}
