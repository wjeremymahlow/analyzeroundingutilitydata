<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ICD10Code extends Model
{
    protected $guarded = [];
    protected $table="icd10codes";

    public function charges()
    {
    	return $this->belongsToMany(Charge::class,'charge_icd10code','charge_id','icd10code_id');
    }
}