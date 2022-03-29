<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Attending extends Model
{
    protected $guarded = [];
    public function charges()
    {
    	return $this->hasMany('App\Charge');
    }
}
