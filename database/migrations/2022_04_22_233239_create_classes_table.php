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
        Schema::create('classes', function (Blueprint $table) {
            $table->char('classID', 8);
            $table->string('className', 100);
            $table->string('day', 10);
            $table->time('startTime');
            $table->time('endTime');
            $table->char('grade', 5);
            $table->string('room', 15);
            $table->decimal('classFee', 7,2);
            $table->string('feeType', 8);
            $table->char('status', 10)->default('Active');
            $table->char('subjectID', 8);
            $table->char('categoryID', 8);
            $table->char('teacherID', 11);
            $table->char('branchID', 8);
            $table->foreign('subjectID')->references('subjectID')->on('subjects');
            $table->foreign('categoryID')->references('categoryID')->on('categories');
            $table->foreign('teacherID')->references('teacherID')->on('teachers');
            $table->foreign('branchID')->references('branchID')->on('branches');
            $table->primary('classID');
        });

        DB::statement('ALTER TABLE classes ADD CHECK (grade IN ("Other") OR grade BETWEEN 1 AND 13);');
        DB::statement('ALTER TABLE classes ADD CHECK(feeType IN ("Daily", "Monthly"));');
        DB::statement('ALTER TABLE classes ADD CHECK(status IN ("Active", "Deactivate"));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
};
