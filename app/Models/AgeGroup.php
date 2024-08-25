<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClassRoom;

class AgeGroup extends Model
{
    use HasFactory;

   protected $hidden=['created_at','updated_at'];


    public function classRooms(){

      return $this->hasMany(ClassRoom::class,'ageGroup_id');

    }

    public function students(){
      return $this->hasMany(Student::class,'level');
    }

    public function subjects(){
      return $this->hasMany(Subject::class,'ageGroup_id');
    }
}
