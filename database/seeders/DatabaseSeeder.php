<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AgeGroup;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolePermissionsSeeder::class,
            UserSeeder::class,
            TeacherSeeder::class,
            ParentSeeder::class,
            AgeGroupsSeeder::class,
            StudentSeeder::class,
            ClassRoomsSeeder::class,
            ClassRoomsTeacherSeeder::class,
            SubjectsSeeder::class,
            TaskSeeder::class
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
