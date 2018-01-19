<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('mtc_id');
            
            $table->string('mtc_code');
            $table->string('mtc_y_code');
            
            $table->unique( array('mtc_code','mtc_y_code') );

            $table->string('mtc_sport_code');
            $table->string('mtc_home_team_code');
            $table->string('mtc_away_team_code');
            $table->integer('mtc_home_score');
            $table->integer('mtc_away_score');
            $table->string('mtc_status_type');
            $table->string('mtc_status');
            $table->boolean('mtc_lineup_available');
            $table->dateTime('mtc_start_time');
            $table->string('mtc_location');
            $table->string('mtc_weather');
            $table->text('mtc_boxscore_link');
            
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
        Schema::drop('matches');
    }
}