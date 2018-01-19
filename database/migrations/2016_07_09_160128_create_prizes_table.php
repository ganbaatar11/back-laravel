<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prizes', function (Blueprint $table) {
            $table->integer('prize_id');
            $table->integer('prize_con_id');
            $table->integer('prize_start_pos');
            $table->integer('prize_end_pos');
            $table->float('prize_amount');
            $table->integer('prize_count');

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
        Schema::drop('prizes');
    }
}