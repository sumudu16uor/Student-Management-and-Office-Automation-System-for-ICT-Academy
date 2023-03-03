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
        Schema::create('user_login_records', function (Blueprint $table) {
            $table->char('userID', 8);
            $table->date('loginDate');
            $table->time('loginTime');
            $table->time('logoutTime')->nullable();
            $table->foreign('userID')->references('userID')->on('users');
            $table->primary(['userID', 'loginDate', 'loginTime']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_login_records');
    }
};
