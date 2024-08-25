<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeworkstudent_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->references('id')->on('students')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('homeworkstudent_id')->references('id')->on('homework_students')
            ->cascadeOnDelete()
            ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homeworkstudent_relationships');
    }
};
