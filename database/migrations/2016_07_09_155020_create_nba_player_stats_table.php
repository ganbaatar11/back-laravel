<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNbaPlayerStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nba_player_stats', function (Blueprint $table) {
            $table->increments('nps_id');
            $table->string('nps_game_code');
            $table->string('nps_player_code');

            $table->unique( array('nps_game_code','nps_player_code') );
            
            $table->string('nps_min');
            $table->float('nps_fpts');
            $table->integer('nps_pt');
            $table->integer('nps_threept');
            $table->integer('nps_reb');
            $table->integer('nps_ast');
            $table->integer('nps_st');
            $table->integer('nps_blk');
            $table->integer('nps_to');
            
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
        Schema::drop('nba_player_stats');
    }
}