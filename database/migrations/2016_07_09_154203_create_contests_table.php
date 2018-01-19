<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->increments('con_id');
            $table->string('con_ych_code');
            $table->string('con_create_user_id');
            $table->string('con_sport_code');
            $table->string('con_type');
            $table->string('con_title');
            $table->integer('con_entry_fee');
            $table->integer('con_entry_count');
            $table->integer('con_entry_limit');
            $table->integer('con_multiple_entry_limit');
            $table->integer('con_total_prize');
            $table->integer('con_salary_cap');
            $table->dateTime('con_start_time');
            $table->boolean('con_multiple_entry');
            $table->string('con_scope');
            $table->boolean('con_guaranteed');
            $table->string('con_state');

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
        Schema::drop('contest');
    }
}