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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',45);
            $table->string('last_name',45);
            $table->enum('gender',['male','female']);
            $table->date('date_of_birth');
            $table->string('phone_number',45);
            $table->string('address');
            $table->text('details');
            $table->foreignId('user_id')
            ->references('id')->on('users')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->bigInteger('national_id');
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
        Schema::dropIfExists('teachers');
    }
};
