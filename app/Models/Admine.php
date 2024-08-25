<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admine extends Model
{
    use HasFactory;
    protected $fillable=['first_name','last_name','user_id'];

    
    public function user(){
        return $this->belongsTo(User::class);
    }
}