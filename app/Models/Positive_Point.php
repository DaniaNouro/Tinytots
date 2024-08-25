<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Teacher ;

class Positive_Point extends Model
{
    use HasFactory;

    protected $fillable=['name','value'];
    protected $hidden=['created_at','updated_at'];

/////Relations
    
    public function evaluations(){

        return $this->belongsToMany(Evaluation::class,'evaluation_positivepoints','evaluation_id','positivePoint_id');
    }

}
