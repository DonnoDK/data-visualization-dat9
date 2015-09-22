<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TestImageCreate extends Migration

{    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_image', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('image_path');
            $table->double('valence', 15, 2);
            $table->string('image_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('test_image');
    }
}