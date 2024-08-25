<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
   
    public function run()
    {
        $students=[
            [
                'first_name'=>'student_1',
                'last_name'=>'last',
                'gender'=>'female',
                'date_of_birth'=>'2020-11-1',
                'address'=>'altal',
                'deatails'=>'Nothing',
                'level'=>'1',
                'user_id'=>'5',
                'parentt_id'=>'1'
            ],
            [
                'first_name'=>'student_2',
                'last_name'=>'last',
                'gender'=>'female',
                'date_of_birth'=>'2022-11-1',
                'address'=>'altal',
                'deatails'=>'Nothing',
                'level'=>'2',
                'user_id'=>'6',
                'parentt_id'=>'2'
            ],
            ];
            foreach($students as $student){
                Student::create([
                    'first_name'=>$student['first_name'],
                    'last_name'=>$student['last_name'],
                    'gender'=>$student['gender'],
                    'date_of_birth'=>$student['date_of_birth'],
                    'level'=>$student['level'],
                    'address'=>$student['address'],
                    'deatails'=>$student['deatails'],
                    'parentt_id'=>$student['parentt_id'],
                    'user_id'=>$student['user_id'],
                ]);
            }
        
    }
}
