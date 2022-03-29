<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class AllBilledICD10CodesExport implements FromCollection
{
	public $allbilledicd10codes;

    public function __construct($allbilledicd10codes)
    {
    	$this->allbilledicd10codes = $allbilledicd10codes;
    }

    public function collection()
    {
        return $this->allbilledicd10codes;
    }
}
