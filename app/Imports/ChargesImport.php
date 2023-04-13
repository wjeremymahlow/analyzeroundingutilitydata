<?php

namespace App\Imports;

use App\Models\Charge;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ChargesImport implements ToModel, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Charge([
            'dateofservice' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[0])->format('m/d/Y'),
            'patientname'    => $row[1], 
            'room'    => $row[2], 
            'roundingmdabbreviations' => strtoupper($row[3]), 
            'cpsmrn'    => $row[4], 
            'powerchartmrn'    => $row[5], 
            'icd10code1'    => $row[6], 
            'icd10code2'    => $row[7], 
            'icd10code3'    => $row[8], 
            'icd10code4'    => $row[9], 
            'referringmd'    => $row[10], 
            'cptcode'    => $row[11], 
            'billingmdabbreviation'    => strtoupper($row[12]), 
            'chargestatus'    => $row[13], 
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
