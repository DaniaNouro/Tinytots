<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable=[
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'level',
        'address',
        'deatails',
        'user_id',
        'parentt_id',
        
    ];
    
    protected $hidden=['created_at','updated_at','pivot'];


///////Relations
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function parent(){

        return $this->belongsTo(Parentt::class,'parentt_id');
    }


    public function class_rooms(){
        return $this->belongsToMany(ClassRoom::class,'class_room_student','student_id','class_room_id');
    }
  
    public function age_group(){
        return $this->belongsTo(AgeGroup::class);
    }
}
