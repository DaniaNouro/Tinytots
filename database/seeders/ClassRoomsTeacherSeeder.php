<?php
namespace Database\Seeders;
use App\Models\AgeGroup;
use App\Models\Teacher; 
use App\Models\ClassRoom;
use Illuminate\Database\Seeder;
use App\Models\ClassRoomTeacher;
use Illuminate\Support\Facades\DB;

class ClassRoomsTeacherSeeder extends Seeder
{
    public function run()
    {
        DB::table('class_room_teacher')->truncate();

        // Retrieve all age groups from the database
        $ageGroups = AgeGroup::all();

        // Teacher ID to assign responsibility
        $teacherId = 1;

        // Seed class_room_teacher records for each age group
        foreach ($ageGroups as $ageGroup) {
            // Retrieve classrooms for the current age group
            $classrooms =ClassRoom::where('ageGroup_id', $ageGroup->id)->get();

            // Assign teacher to each classroom in this age group
            foreach ($classrooms as $classroom) {
                ClassRoomTeacher::create([
                    'class_room_id' => $classroom->id,
                    'teacher_id' => $teacherId,
                ]);
            }
        }
    }
}
