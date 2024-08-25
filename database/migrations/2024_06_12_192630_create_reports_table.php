<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    { Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->references('id')->on('students')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
        $table->foreignId('teacher_id')->references('id')->on('teachers')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
        $table->date('dateOfReport');
        $table->date('start_date');
        $table->date('end_date');
        $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
