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
        Schema::create('people', function (Blueprint $table) {
            $table->char('personID', 11);
            $table->string('personType', 100);
            $table->string('firstName', 50);
            $table->string('lastName', 50)->nullable();
            $table->date('dob');
            $table->char('sex', 6);
            $table->char('telNo', 10);
            $table->string('address', 150);
            $table->string('email', 50)->nullable()->unique();
            $table->char('status', 10)->default('Active');
            $table->date('joinedDate');
            $table->primary('personID');
        });

        DB::statement('ALTER TABLE people ADD CHECK(personType IN ("Student", "Employee"));');
        DB::statement('ALTER TABLE people ADD CHECK(sex IN ("Male", "Female", "Other"));');
        DB::statement('ALTER TABLE people ADD CHECK(status IN ("Super", "Active", "Past", "Deactivate"));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people');
    }
};
