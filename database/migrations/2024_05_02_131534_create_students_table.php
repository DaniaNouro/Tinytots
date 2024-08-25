<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Enum;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',45);
            $table->string('last_name',45);
            $table->enum('gender',['male','female']);
            $table->date('date_of_birth');
            $table->string('address');
            $table->text('deatails');
            $table->foreignId('user_id')
            ->references('id')->on('users')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('level')->references('id')->on('age_groups');
            $table->foreignId('parentt_id')->references('id')->on('parentts');
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
        Schema::dropIfExists('students');
    }
};
