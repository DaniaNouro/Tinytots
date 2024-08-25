<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parentt extends Model
{
    use HasFactory;

    protected $fillable =[
       'father_first_name',
       'father_last_name',
       'father_phone_number',
       'mother_first_name',
       'mother_last_name',
       'mother_phone_number',
       'national_id',
       'user_id'
    ];

    protected $hidden=['created_at','updated_at'];
    /////Relations
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function students(){
        return $this->hasMany(Student::class,'parentt_id');
    }
  




}
