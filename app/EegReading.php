<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EegReading extends Model
{
    protected $table = "eeg_reading";
    protected $fillable = array('test_case_id', 'channel_id', 'value');

    public function test_case_id(){
    	return $this->hasOne('App\test_case');
    }

    public function channel_id(){
    	return $this->hasOne('App\EegChannel');
    }
}
