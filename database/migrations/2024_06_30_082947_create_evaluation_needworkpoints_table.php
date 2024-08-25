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
        Schema::create('evaluation_needworkpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->references('id')->on('evaluations')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('needworkPoint_id')->references('id')->on('need_works')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->string('notes');
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
        Schema::dropIfExists('evaluation_needworkpoints');
    }
};
