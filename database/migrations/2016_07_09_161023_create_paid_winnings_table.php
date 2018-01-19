<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidWinningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_winnings', function (Blueprint $table) {
            $table->integer('pw_id');
            $table->float('pw_value');
            $table->string('pw_currency');
            $table->string('pw_status');
            $table->integer('pw_prize_id');

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
        Schema::drop('paid_winnings');
    }
}
