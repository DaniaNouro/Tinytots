<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoomTeacher extends Model
{
    use HasFactory;

    protected $table = 'class_room_teacher';
    protected $fillable = ['class_room_id', 'teacher_id', 'start_date', 'end_date'];
}
