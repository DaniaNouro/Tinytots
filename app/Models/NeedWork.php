<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeedWork extends Model
{
    use HasFactory;

    protected $fillable=['name','value'];
    protected $hidden=['created_at','updated_at'];


    public function evaluations(){

        return $this->belongsToMany(Evaluation::class,'evaluation_needworkpoints','evaluation_id','needworkPoint_id');
    }



}
