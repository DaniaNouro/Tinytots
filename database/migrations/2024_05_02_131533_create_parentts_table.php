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
        Schema::create('parentts', function (Blueprint $table) {
            $table->id();
            $table->string('father_first_name',45);
            $table->string('father_last_name',45);
            $table->string('father_phone_number',45);
            $table->string('mother_first_name',45);
            $table->string('mother_last_name',45);
            $table->string('mother_phone_number',45);
            $table->bigInteger('national_id');
            $table->foreignId('user_id')
            ->references('id')->on('users')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
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
        Schema::dropIfExists('parentts');
    }
};
