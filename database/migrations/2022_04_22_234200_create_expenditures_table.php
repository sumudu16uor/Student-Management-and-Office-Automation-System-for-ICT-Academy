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
        Schema::create('expenditures', function (Blueprint $table) {
            $table->id('expenseID');
            $table->string('expense', 100);
            $table->decimal('expenseAmount', 8, 2);
            $table->date('date');
            $table->char('handlerStaffID', 11);
            $table->char('branchID', 8);
            $table->softDeletes();
            $table->foreign('handlerStaffID')->references('staffID')->on('staff');
            $table->foreign('branchID')->references('branchID')->on('branches');
            //$table->primary('expenseID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenditures');
    }
};
