<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TestDataCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_data', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('test_person_id')->unsigned();
            $table->integer('test_case_id')->unsigned();
            $table->string('image_path');
            $table->string('image_type');
            $table->float('image_control_valence');
            $table->integer('test_person_valence');
            $table->integer('timestamp_start');
            $table->integer('timestamp_end');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('test_data');
    }
}
