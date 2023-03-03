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
        Schema::create('students', function (Blueprint $table) {
            $table->char('studentID', 11);
            $table->char('grade', 5);
            $table->char('branchID', 8);
            $table->foreign('studentID')->references('personID')->on('people')->cascadeOnDelete();
            $table->foreign('branchID')->references('branchID')->on('branches');
            $table->primary('studentID');
        });

        DB::statement('ALTER TABLE students ADD CHECK (grade IN ("Other") OR grade BETWEEN 1 AND 13);');
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
