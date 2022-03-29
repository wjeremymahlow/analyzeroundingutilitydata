<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendingCensusForDate implements FromCollection, WithHeadings
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
            'date',
            'attending',
            'number of patients billed',
            'number of charges billed',
        ];
    }


}
