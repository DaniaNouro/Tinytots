<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation_Teacher extends Model
{
    use HasFactory;

    protected $fillable=[
        'teacher_id','evaluation_id'

    ];



}
