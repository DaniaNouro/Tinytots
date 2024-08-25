<?php

namespace Database\Seeders;

use App\Models\User;

use App\Models\Parentt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{

    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $parentRole = Role::where('name', 'parent')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $adminRole = Role::where('name', 'admine')->first();
        $studentRole = Role::where('name', 'student')->first();
      
      $users= [
        //parents
        [
            'email' => 'parent_1@gmail.com',
            'password' => Hash::make('11111111111'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'role'=>$parentRole
        ],
        [
            'email' => 'parent_2@example.com',
            'password' => Hash::make('11111111112'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'role'=>$parentRole
        ],
        //teacher
        [
            'email' => 'teacher@example.com',
            'password' => Hash::make('11223344555'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'role'=>$teacherRole

        ],
       // admine
        [
        'email' => 'admine@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'role'=>$adminRole
        ],
        //students
        [
        'email' => 'parent_1@example.com',
        'password' => Hash::make('1001'),
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
        'created_at' => now(),
        'updated_at' => now(),
        'role'=> $studentRole 
        ],
        [
        'email' => 'parent_2@example.com',
        'password' => Hash::make('1002'),
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
        'created_at' => now(),
        'updated_at' => now(),
        'role'=> $studentRole 
        ]
    ];
    foreach ($users as $userData) {
        $user = User::create([
            'email' => $userData['email'],
            'password' => $userData['password'], // استخدم كلمة مرور افتراضية أو فريدة لكل مستخدم
            'email_verified_at' => $userData['email_verified_at'],
            'remember_token' => $userData['remember_token'],
            'created_at' =>$userData['created_at'] ,
            'updated_at' => $userData['updated_at'],
        ]);
        // إسناد الدور للمستخدم
        $user->assignRole($userData['role']);
        $Permissions=$userData['role']->permissions()->pluck('name')->toArray();//we put toarrray because givepermissinto input (array)
        $user->givePermissionTo($Permissions);
    }
    }
}