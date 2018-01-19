<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYCodeHoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('y_code_holders', function (Blueprint $table) {
            $table->increments('ych_id');
            $table->string('ych_code');
            $table->string('ych_state');
            $table->dateTime('ych_start_time')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('y_code_holders');
    }
}
