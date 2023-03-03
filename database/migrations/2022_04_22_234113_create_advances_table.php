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
        Schema::create('advances', function (Blueprint $table) {
            $table->id('advanceID');
            $table->string('description', 100);
            $table->decimal('advanceAmount', 8, 2);
            $table->date('date');
            $table->char('employeeID', 11);
            $table->char('handlerStaffID', 11);
            $table->char('branchID', 8);
            $table->softDeletes();
            $table->foreign('employeeID')->references('employeeID')->on('employees');
            $table->foreign('handlerStaffID')->references('staffID')->on('staff');
            $table->foreign('branchID')->references('branchID')->on('branches');
            //$table->primary('advanceID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advances');
    }
};
