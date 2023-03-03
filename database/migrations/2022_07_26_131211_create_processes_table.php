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
        Schema::create('processes', function (Blueprint $table) {
            $table->id('processID');
            $table->string('processType', 20);
            $table->dateTime('updated_at');
            $table->char('handlerStaffID', 11);
            $table->char('branchID', 8);
            $table->softDeletes();
            $table->foreign('handlerStaffID')->references('staffID')->on('staff');
            $table->foreign('branchID')->references('branchID')->on('branches');
            //$table->primary('processID');
        });

        DB::statement('ALTER TABLE processes ADD CHECK (processType IN ("month_end", "year_end", "ol_batch_end", "al_batch_end" , "clear_login"));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processes');
    }
};
