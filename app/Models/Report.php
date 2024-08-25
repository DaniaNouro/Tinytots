<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    
        use HasFactory;
    
        protected $fillable=['student_id','teacher_id','dateOfReport','start_date','end_date'];
    
    ////Relation
        public function student(){
            return $this->belongsTo(Student::class,'student_id');
        }
    
        public function teacher(){
            return $this->belongsTo(Teacher::class,'teacher_id');
        }
}
