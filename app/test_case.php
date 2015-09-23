<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class test_case extends Model
{
    protected $table = 'test_case';

    public function testPerson(){
    	return $this->belongsTo('App\testPerson');
    }
}
