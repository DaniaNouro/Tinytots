<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'media_path',
        'visibility'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visibilities()
    {
        return $this->hasMany(post_visibility::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }



    // Scope for admin
    public function scopeForAdmin(Builder $query)
    {
        return $query;
    }

    // Scope for teachers
    public function scopeForTeachers(Builder $query)
    {
        return $query->where('visibility', 'teachers')
                     ->orWhere('visibility', 'everyone')
                     ->orwhere('visibility','class');
    }

    // Scope for students
    public function scopeForStudents(Builder $query)
    {
        return $query->where('visibility', 'everyone');
    }

    

    // Scope for parents
public function scopeForParents(Builder $query, $studentClasses)
{
    return $query->where('visibility', 'everyone')
                 ->orWhereHas('visibilities', function($query) use ($studentClasses) {
                     $query->whereIn('id', $studentClasses);
                 });
}

}

