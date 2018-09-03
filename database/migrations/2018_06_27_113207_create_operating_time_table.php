<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperatingTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operating_time', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start')->nullalble();
            $table->dateTime('finish')->nullalble();
            $table->integer('computer_id');
            $table->string('account_id');
            $table->unique(['start', 'finish', 'account_id', 'computer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operating_time');
    }
}
