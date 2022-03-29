<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendingCensusForDate2 implements FromCollection, WithHeadings
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
            'attending',
            'average number of patients billed',
            'min number of patients billed',
            'max number of patients billed',
        ];
    }


}
