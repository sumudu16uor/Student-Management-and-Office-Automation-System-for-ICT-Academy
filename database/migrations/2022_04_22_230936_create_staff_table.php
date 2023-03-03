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
        Schema::create('staff', function (Blueprint $table) {
            $table->char('staffID', 11);
            $table->char('branchID', 8);
            $table->foreign('staffID')->references('employeeID')->on('employees')->cascadeOnDelete();
            $table->foreign('branchID')->references('branchID')->on('branches');
            $table->primary('staffID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
};
