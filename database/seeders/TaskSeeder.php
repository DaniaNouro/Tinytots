<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /*
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = [
            ['task_name' => 'Drawing'],
            ['task_name' => 'Cleaning the classroom'],
            ['task_name' => 'Coloring a picture'],
            ['task_name' => 'Organizing toys'],
            ['task_name' => 'Dusting'],
            ['task_name' => 'Washing dishes'],
            ['task_name' => 'Reading a story'],
            ['task_name' => 'Playing a game'],
        ];

        foreach ($tasks as $task) {
            Task::create([
                'task_name' => $task['task_name'],
            ]);
        }
    }
}