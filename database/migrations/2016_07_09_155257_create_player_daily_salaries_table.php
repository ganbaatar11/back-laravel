<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerDailySalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_daily_salaries', function (Blueprint $table) {
            $table->increments('pds_id');
            $table->string('pds_ych_code');
            $table->string('pds_player_code');
            $table->integer('pds_player_salary');
            $table->dateTime('pds_date');
            
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
        Schema::drop('player_daily_salaries');
    }
}