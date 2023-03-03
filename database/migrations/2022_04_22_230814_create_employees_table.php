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
        Schema::create('employees', function (Blueprint $table) {
            $table->char('employeeID', 11);
            $table->string('employeeType', 100);
            $table->char('nic', 12);
            $table->char('title', 5);
            $table->foreign('employeeID')->references('personID')->on('people')->cascadeOnDelete();
            $table->primary('employeeID');
        });

        DB::statement('ALTER TABLE employees ADD CHECK(employeeType IN ("Staff", "Teacher"));');
        DB::statement('ALTER TABLE employees ADD CHECK(title IN ("Mr.", "Miss.", "Mrs.", "Ms.", "Rev."));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
