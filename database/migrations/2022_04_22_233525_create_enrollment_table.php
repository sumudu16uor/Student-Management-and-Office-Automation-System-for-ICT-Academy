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
        Schema::create('enrollment', function (Blueprint $table) {
            $table->char('studentID', 11);
            $table->char('classID', 8);
            $table->integer('paymentStatus')->default(1)->comment('Paid >= 0, Free = -1');
            $table->date('enrolledDate');
            $table->smallInteger('status')->default(1)->comment('Active = 1, Deactivate = 0');
            $table->foreign('studentID')->references('studentID')->on('students')->cascadeOnDelete();
            $table->foreign('classID')->references('classID')->on('classes')->cascadeOnDelete();
            $table->primary(['studentID','classID']);
        });

        DB::statement('ALTER TABLE enrollment ADD CHECK (paymentStatus >= -1);');
        DB::statement('ALTER TABLE enrollment ADD CHECK(status IN (0, 1));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollment');
    }
};
