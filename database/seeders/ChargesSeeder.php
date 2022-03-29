<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Imports\ChargesImport;
use Maatwebsite\Excel\Facades\Excel;

class ChargesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	dump('Seeding Charges...');
        Excel::import(new ChargesImport, '/forimport/billingdata1.xlsx');
        Excel::import(new ChargesImport, '/forimport/billingdata2.xlsx');
        dump('Done Seeding Charges');
    }
}
