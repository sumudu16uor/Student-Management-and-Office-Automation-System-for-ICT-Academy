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
        Schema::create('subjects', function (Blueprint $table) {
            $table->char('subjectID', 8);
            $table->string('subjectName', 50);
            $table->char('medium', 7);
            $table->char('categoryID', 8);
            $table->foreign('categoryID')->references('categoryID')->on('categories');
            $table->primary('subjectID');
        });

        DB::statement('ALTER TABLE subjects ADD CHECK (medium IN ("Sinhala", "English", "Tamil"));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};
