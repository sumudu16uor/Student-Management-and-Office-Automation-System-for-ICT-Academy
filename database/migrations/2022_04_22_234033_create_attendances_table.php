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
        Schema::create('attendances', function (Blueprint $table) {
            $table->char('studentID', 11);
            $table->char('classID', 8);
            $table->date('date');
            $table->time('time')->nullable();
            $table->smallInteger('attendStatus')->default(0)->comment('Present = 1, Absent = 0');
            $table->foreign('studentID')->references('studentID')->on('students')->cascadeOnDelete();
            $table->foreign('classID')->references('classID')->on('classes')->cascadeOnDelete();
            $table->primary(['studentID','classID', 'date']);
        });

        DB::statement('ALTER TABLE attendances ADD CHECK(attendStatus IN (0, 1));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
