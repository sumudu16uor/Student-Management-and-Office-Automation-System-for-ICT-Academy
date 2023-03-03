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
        Schema::create('exams', function (Blueprint $table) {
            $table->char('examID', 8);
            $table->string('exam', 100);
            $table->smallInteger('totalMark')->default(100);
            $table->date('date');
            $table->char('classID', 8);
            $table->char('subjectID', 8);
            $table->char('categoryID', 8);
            $table->char('branchID', 8);
            $table->foreign('classID')->references('classID')->on('classes');
            $table->foreign('subjectID')->references('subjectID')->on('subjects');
            $table->foreign('categoryID')->references('categoryID')->on('categories');
            $table->foreign('branchID')->references('branchID')->on('branches');
            $table->primary('examID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
};
