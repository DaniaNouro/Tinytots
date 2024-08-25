<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationNeedworkpoint extends Model
{
    use HasFactory;
    protected $fillable=['evaluation_id','needworkPoint_id'];
    
}
