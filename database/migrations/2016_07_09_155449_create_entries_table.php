<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->increments('ent_id');
            $table->integer('ent_con_id');
            $table->integer('ent_lu_id');
            $table->integer('ent_user_id');
            $table->float('ent_score');
            $table->integer('ent_winnings');
            $table->integer('ent_pw_id');
            $table->integer('ent_pef_id');
            $table->dateTime('ent_canceled_at')->nullable();
            $table->boolean('ent_cancelable')->nullable();
            $table->float('ent_maximum_point')->nullable();
            $table->float('ent_profitable_points')->nullable();
            $table->float('ent_minimum_points')->nullable();
            $table->float('ent_projected_points')->nullable();
            $table->float('ent_periods_remaining')->nullable();
            $table->integer('ent_remaining_time_unit')->nullable();
            $table->integer('ent_total_time_unit');

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
        Schema::drop('entries');
    }

}
