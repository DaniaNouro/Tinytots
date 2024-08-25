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
        Schema::create('homework_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homework_id')->references('id')->on('home_works')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->string('uploaded_homework');
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
        Schema::dropIfExists('homework_students');
    }
};
