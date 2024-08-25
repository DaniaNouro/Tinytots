<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AgeGroup;

class ClassRoomsSeeder extends Seeder
{
   
    public function run()
    {
        // Retrieve all age groups from the database
        $ageGroups = AgeGroup::all();

        // Seed classrooms for each age group
        foreach ($ageGroups as $ageGroup) {
            for ($i = 1; $i <= 3; $i++) {
                DB::table('class_rooms')->insert([
                    'class_name' => $ageGroup->name . ' - Class ' . $i,
                    'capacity' => 20,
                    'ageGroup_id' => $ageGroup->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
