<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $guarded = [];
    public function attending()
    {
    	return $this->belongsTo('App\Attending');
    }

    public function date()
    {
    	return $this->belongsTo('App\Date');
    }

    public function patient()
    {
    	return $this->belongsTo('App\Patient');
    }

    public function icd10codes()
    {
    	return $this->belongsToMany('App\ICD10Code','charge_icd10code','charge_id','icd10code_id');
    }

    public function formattedcptcode()
    {
        return preg_replace('/[^0-9]/i', '', $this->cptcode);
    }

    protected function getfirstletters($string, $numberofletters)
    {
        return strtolower(substr($string,0,$numberofletters));
    }

    public function isincvicu()
    {
        return ($this->getfirstletters($this->room,1) == 'c' && $this->getfirstletters($this->room,3) != 'cvr' && $this->getfirstletters($this->room,2) != 'co' && $this->getfirstletters($this->room,2) != 'c0');
    }

    public function isinmcc()
    {
        return ($this->getfirstletters($this->room,2) == 'mc'|| $this->getfirstletters($this->room,2) == 'mi');
    }

    public function isintsicu()
    {
        return ($this->getfirstletters($this->room,2) == 'ts' || $this->getfirstletters($this->room,2) == 'si');
    }

    public function isinncc()
    {
        return ($this->getfirstletters($this->room,2) == 'nc' || $this->getfirstletters($this->room,2) == 'ni');
    }

    public function isincvr()
    {
        return ($this->getfirstletters($this->room,3) == 'cvr');
    }

    public function isinnoncvicumcctsicuncccvr()
    {
        return (!$this->isincvicu() && !$this->isinmcc() && !$this->isintsicu() && !$this->isinncc() && !$this->isincvr());
    }

    public function roundingmdabbreviations()
    {
        return collect(explode('/', $this->roundingmdabbreviations));
    }

}
