<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestData extends Model
{
    protected $table = 'test_data';
    public $timestamps = false;

    public function test_case(){
    	return $this->belongsTo('App\test_case');
    }

    public function testPerson(){
    	return $this->belongsTo('App\testPerson');
    }
}
