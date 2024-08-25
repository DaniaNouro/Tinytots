<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectsSeeder extends Seeder
{
    
    public function run()
    {
      // أو يمكنك استخدام أي منطق آخر للحصول على رقم الفئة المناسبة

        // إضافة بيانات المواد
        Subject::create([
            'name' => 'Mathematics',
            'file_path' => '/subjects/Arabic.pdf',
            'ageGroup_id' => 1,
        ]);

        Subject::create([
            'name' => 'Science',
            'file_path' => '/subjects/math.pdf',
            'ageGroup_id' => 2,
        ]);

        Subject::create([
            'name' => 'History',
            'file_path' => '/subjects/math.pdf',
            'ageGroup_id' =>3,
        ]);
    }
    }

