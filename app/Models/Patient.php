<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $guarded = [];
    public function charges()
    {
    	return $this->hasMany(Charge::class);
    }
}
