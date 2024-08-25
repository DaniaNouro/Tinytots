<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'phone_number',
        'address',
        'details',
        'national_id'
    ];


    protected $hidden = ['created_at', 'updated_at'];

    //relationship
    public function user()
    {

        return $this->belongsTo(User::class);
    }

    public function evaluations()
    {

        return $this->belongsToMany(Evaluation::class, 'evaluation__teachers');
    }

    public function needworks()
    {

        return $this->hasMany(NeedWork::class, 'teacher_id');
    }

    public function positive_points()
    {

        return $this->hasMany(Positive_Point::class, 'teacher_id');
    }

    public function posts()
    {

        return $this->hasMany(Post::class, 'teacher_id');
    }

    public function groups()
    {

        return $this->hasMany(Group::class, 'teacher_id');
    }

    public function reports()
    {

        return $this->hasMany(Report::class, 'teacher_id');
    }

    public function homeworks()
    {

        return $this->hasMany(home_work::class, 'teacher_id');
    }

    public function lessons()
    {

        return $this->hasMany(Lesson::class, 'teacher_id');
    }

    public function class_rooms()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_teacher', 'teacher_id', 'class_room_id');
    }
}
