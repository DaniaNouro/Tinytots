<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path');
            $table->foreignId('ageGroup_id')
            ->references('id')->on('age_groups')
            ->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

 
    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};
