<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_student_task extends Model
{
    use HasFactory;

    protected $fillable=['task_id','group_student_id'];
  
}
