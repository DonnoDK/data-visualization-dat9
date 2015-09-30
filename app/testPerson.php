<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class testPerson extends Model
{
    protected $table = 'test_person';
	protected $fillable = array('name', 'occupation', 'age', 'sex');
	public $timestamps = false;

    public function test_case(){
    	return $this->hasMany('App\test_case');
    }

    public function test_data(){
    	return $this->hasMany('App\TestData');
    }
}
