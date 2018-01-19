<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('player_code')->unique();;
            $table->string('player_firstname');
            $table->string('player_lastname');
            $table->string('player_sport_code');
            $table->integer('player_number');
            $table->integer('player_jersey_number');
            $table->string('player_status');
            $table->text('player_image_url');
            $table->text('player_large_image_url');
            $table->integer('player_team_id');
            $table->integer('player_salary');
            $table->integer('player_original_salary');
            $table->float('player_projected_points');
            $table->string('player_starting');
            $table->integer('player_pos_id');
            $table->string('player_primary_position');
            $table->string('player_eligible_position');
            $table->float('player_fantasy_points_per_game');
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
        Schema::drop('players');
    }
}
