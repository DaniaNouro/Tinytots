<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkstudentRelationship extends Model
{
    use HasFactory;
    protected $fillable=['homeworkstudent_id','student_id'];

    
}
