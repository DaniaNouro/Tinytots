<?php

namespace Database\Seeders;

use App\Models\Parentt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParentSeeder extends Seeder
{
   
    public function run()
    {
        $parents=[
            [
                'father_first_name'=>'father',
                'father_last_name'=>'last',
                'father_phone_number'=>'0962183478',
                'mother_first_name'=>'mother',
                'mother_last_name'=>'last',
                'mother_phone_number'=>'0958741253',
                'national_id'=>'11111111111',
                'user_id'=>'1'
              
            ],
            [
                'father_first_name'=>'father_2',
                'father_last_name'=>'last',
                'father_phone_number'=>'0962183479',
                'mother_first_name'=>'mother_2',
                'mother_last_name'=>'last',
                'mother_phone_number'=>'0958741257',
                'national_id'=>'11111111112',
                'user_id'=>'2'
              
            ]
            ];
            foreach($parents as $parent){
                    Parentt::create([
                    'father_first_name'=>$parent['father_first_name'],
                    'father_last_name'=>$parent['father_last_name'],
                    'father_phone_number'=>$parent['father_phone_number'],
                    'mother_first_name'=>$parent['mother_first_name'],
                    'mother_last_name'=>$parent['mother_last_name'],
                    'mother_phone_number'=>$parent['mother_phone_number'],
                    'national_id'=>$parent['national_id'],
                    'user_id'=>$parent['user_id'],
                ]);
            }
        
    }
        
    }
