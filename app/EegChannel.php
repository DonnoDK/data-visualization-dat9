<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EegChannel extends Model
{
    protected $table = "eeg_channel";
    protected $fillable = array('name');
    public $timestamps = false;

}
