<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

    class TeacherSeeder extends Seeder
    {
    
        public function run()
        {
            $teachers=[
            [
                'first_name'=>'Teacher_1',
                'last_name'=>'last',
                'gender'=>'female',
                'date_of_birth'=>'1990-11-1',
                'phone_number'=>'0962183478',
                'address'=>'altal',
                'details'=>'Nothing',
                'national_id'=>'11223344555',
                'user_id'=>'3'
            ]
            ];
            foreach($teachers as $teacher){
                Teacher::create([
                    'first_name'=>$teacher['first_name'],
                    'last_name'=>$teacher['last_name'],
                    'gender'=>$teacher['gender'],
                    'date_of_birth'=>$teacher['date_of_birth'],
                    'phone_number'=>$teacher['phone_number'],
                    'address'=>$teacher['address'],
                    'details'=>$teacher['details'],
                    'national_id'=>$teacher['national_id'],
                    'user_id'=>$teacher['user_id'],
                ]);
            }
        
    }
}
