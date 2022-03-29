<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Imports\ICD10CodesImport;
use Maatwebsite\Excel\Facades\Excel;

class IcdCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        dump('Seeding ICD10Codes...');
        Excel::import(new ICD10CodesImport, '/forimport/icd10codes.csv');

    }
}
