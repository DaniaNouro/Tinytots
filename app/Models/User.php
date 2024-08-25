<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Responces\Response;
use Dotenv\Exception\ValidationException;
use Error;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
       // 'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
         'password',
         'remember_token',
          'created_at',
         'updated_at' ,
         'email_verified_at'];

    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
 
        static::creating(function ($user) {
            if ($user->role !== 'student' && User::where('email', $user->email)->exists()) {
              $data=" ";
              $emailMessage= 'البريد الإلكتروني مستخدم بالفعل.';
              return Response::Error($data,$emailMessage);
            }
        });
    }

//relationship 
public function teacher(){
return $this->hasOne(Teacher::class);
}
public function student(){
 return $this->hasOne(Student::class,"user_id");
}
public function parent(){
return $this->hasOne(Parentt::class);
}
public function Admine(){
return $this->hasOne(Admine::class);
}





}
