<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GsrReading extends Model
{
    protected $table = "gsr_reading";
    protected $fillable = array('test_case_id', 'value', 'timestamp');
    public $timestamps = false;

    public function testCase(){
    	return $this->belongsTo('App\test_case');
    }
}
