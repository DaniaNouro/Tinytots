<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_student extends Model
{
    use HasFactory;
    protected $fillable=['student_id','group_id'];

    public function tasks(){

        return $this->belongsToMany(Task::class,'group_student_tasks','task_id','group_student_id');
       }
}
