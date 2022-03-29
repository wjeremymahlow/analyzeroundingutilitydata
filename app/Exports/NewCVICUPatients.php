<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewCVICUPatients implements FromCollection, WithHeadings
{

	public $cvicucharges;

    public function __construct($cvicucharges)
    {
    	$this->cvicucharges = $cvicucharges;
    }

    public function collection()
    {
        return $this->cvicucharges;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Patient Name',
            'Room Number',
            'MRN',
            'Billing MD',
        ];
    }
}
