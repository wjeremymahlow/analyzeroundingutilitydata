<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RoundingNumbersForAttendings implements FromCollection, WithHeadings
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
            'average daily census',
            'minmum daily census',
            'maximum daily census',
        ];
    }
}
