<?php

namespace App\Models;

use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
   protected $fillable=['classroom_id','date','student_id','status'];
   protected $hidden=['created_at', 'updated_at'];
    use HasFactory;
    /////Relations

    public function classroom(){
        
       return  $this->belongsTo(ClassRoom::class,'classroom_id');
    } 

    public function student(){

       return  $this->belongsTo(Student::class,'student_id');
    }

}
