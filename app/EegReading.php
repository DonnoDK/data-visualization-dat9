<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EegReading extends Model
{
    protected $table = "eeg_reading";
    protected $fillable = array('test_case_id', 'channel_id', 'value', 'timestamp');
    public $timestamps = false;

    public function testCase(){
    	return $this->belongsTo('App\test_case');
    }

    public function channel(){
    	return $this->belongsTo('App\EegChannel');
    }
}
