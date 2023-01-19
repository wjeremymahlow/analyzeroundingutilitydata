<?php

namespace App\Imports;

use App\Models\ICD10Code;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ICD10CodesImport implements ToModel, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ICD10Code([
            'code'     => substr($row[0],0,3) . '.' . substr($row[0],3,),
            'description'    => $row[1],
        ]);
    }

    public function chunkSize(): int
    {
        return 10000;
    }
}
