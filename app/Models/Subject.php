<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable=['name','file_path','ageGroup_id'];
    protected $hidden=['created_at','updated_at'];
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    public function age_group(){
        return $this->belongsTo(AgeGroup::class,'ageGroup_id');
    }

}
