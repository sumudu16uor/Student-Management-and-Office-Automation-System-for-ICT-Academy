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
        Schema::create('parents', function (Blueprint $table) {
            $table->char('studentID', 11);
            $table->char('title', 5);
            $table->string('parentName', 50);
            $table->char('parentType', 10);
            $table->char('telNo', 10);
            $table->foreign('studentID')->references('studentID')->on('students')->cascadeOnDelete();
            $table->primary(['studentID', 'parentName']);
        });

        DB::statement('ALTER TABLE parents ADD CHECK (title IN ("Mr.", "Mrs."));');
        DB::statement('ALTER TABLE parents ADD CHECK (parentType IN ("Father", "Mother", "Guardian"));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parents');
    }
};
