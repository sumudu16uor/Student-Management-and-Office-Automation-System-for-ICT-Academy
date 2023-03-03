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
        Schema::create('mark', function (Blueprint $table) {
            $table->char('examID', 8);
            $table->char('studentID', 11);
            $table->string('mark', 3)->default("Ab")->comment('Absent = Ab');
            $table->foreign('examID')->references('examID')->on('exams')->cascadeOnDelete();
            $table->foreign('studentID')->references('studentID')->on('students')->cascadeOnDelete();
            $table->primary(['examID', 'studentID']);
        });

        DB::statement('ALTER TABLE mark ADD CHECK (mark IN ("Ab") OR mark >= 0);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mark');
    }
};
