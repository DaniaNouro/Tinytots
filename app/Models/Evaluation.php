<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable=[
        'date'
    ];



    ///Relationship

    public function teachers(){

        return $this->belongsToMany(Teacher::class,'evaluation__teachers');
    }

    public function students(){

        return $this->belongsToMany(Student::class,'evaluation__students');
    }

    public function positivePoints(){

        return $this->belongsToMany(Positive_Point::class,'evaluation_positivepoints','evaluation_id','positivePoint_id');
    }

    public function needworkPoints(){

        return $this->belongsToMany(NeedWork::class,'evaluation_needworkpoints','evaluation_id','needworkPoint_id');
    }
}
