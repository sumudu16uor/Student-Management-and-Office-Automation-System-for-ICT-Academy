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
        Schema::create('branches', function (Blueprint $table) {
            $table->char('branchID', 8);
            $table->string('branchName', 50);
            $table->char('telNo', 10)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('noOfRooms', 2)->nullable();
            $table->primary('branchID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
};
