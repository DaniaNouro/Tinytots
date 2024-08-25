<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationPositivepoint extends Model
{
    use HasFactory;
    protected $fillable=['evaluation_id','positivePoint_id'];
}
