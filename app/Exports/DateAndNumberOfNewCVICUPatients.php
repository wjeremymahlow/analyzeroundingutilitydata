<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DateAndNumberOfNewCVICUPatients implements FromCollection, WithHeadings
{
	public $rows;

    public function __construct($rows)
    {
    	$this->rows = $rows;
    }

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Number of New CVICU Patients',
        ];
    }
}
