<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineupSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineup_slots', function (Blueprint $table) {
            $table->increments('lus_id');
            $table->integer('lus_lu_id');
            $table->string('lus_player_code');
            $table->integer('lus_pos_id');
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
        Schema::drop('lineup_slots');
    }
}