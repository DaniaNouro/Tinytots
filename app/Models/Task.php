<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable=['task_name'];
    protected $hidden=['created_at','updated_at'];

////Relation

   public function groupStudents(){
    
    return $this->belongsToMany(Group_student::class,'group_student_tasks','task_id','group_student_id');
   }
}
