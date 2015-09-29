<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class testPerson extends Model
{
    protected $table = 'test_person';
	protected $fillable = array('name', 'occupation', 'age');

    public function test_case(){
    	return $this->hasMany('App\test_case');
    }
}
