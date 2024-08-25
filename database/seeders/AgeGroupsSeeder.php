<?php

namespace Database\Seeders;

use App\Models\AgeGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgeGroupsSeeder extends Seeder
{
  
    public function run()
    {
       $ageGroups=[
       [
       'name'=>'Kg1',
       'description'=>'from 3_years to 4_years'
       ],
       [
       'name'=>'Kg2',
       'description'=>'from 4_years to 5_years'
       ],
       [
        'name'=>'Kg3',
       'description'=>'from 5_years to 6_years'
       ]

       ];

       foreach( $ageGroups as $agegroup){

        AgeGroup::create(
            [
            'name'=>$agegroup['name'],
            'description'=>$agegroup['description']

            ]
            );
       }
    }
}
