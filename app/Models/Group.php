<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable=['group_name','teacher_id','task_id','class_id'];
    protected $hidden = ['created_at', 'updated_at'];
    
/////Relations
     
    public function teacher(){

        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function students(){

        return $this->belongsToMany(Student::class,'group_students');
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }
    public function class_rooms(){

        return $this->belongsTo(ClassRoom::class,'class_id');
    }
}
