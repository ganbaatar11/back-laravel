<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidEntryFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_entry_fees', function (Blueprint $table) {
            $table->integer('pef_id');
            $table->float('pef_value');
            $table->string('pef_currency');
            $table->string('pef_status');
            $table->float('pef_amount');

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
        Schema::drop('paid_entry_fees');
    }
}