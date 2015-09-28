<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GsrReadingCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsr_reading', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('test_case_id')->unsigned();
            $table->integer('value');
            $table->integer('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return voide
     */
    public function down()
    {
        Schema::drop('gsr_reading');
    }
}
