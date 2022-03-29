<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Exports\AllBilledICD10CodesExport;
use Maatwebsite\Excel\Facades\Excel;

class GetAllBilledICD10Codes extends Seeder
{
    protected $allbilledicd10codes;

    public function __construct()
    {
    	$this->allbilledicd10codes = collect();
    }

    public function run()
    {
    	dump('Getting all billed icd10 codes...');
        Charge::chunk(500,function($charges) {
        	echo '.';
        	$charges->each(function($charge) {
        		$charge->icd10codes->each(function($icd10code) {
        			$existingicd10code = $this->allbilledicd10codes->where('id',$icd10code->id)->first();
        			if (! $existingicd10code) {
        				$icd10code->numberoftimesbilled = 1;
        				$this->allbilledicd10codes->push($icd10code);

        			} else {
        				$existingicd10code->numberoftimesbilled += 1;
        			}
        		});
        	});
        });
        return Excel::store(new AllBilledICD10CodesExport($this->allbilledicd10codes), 'allbilledicd10codes.xlsx');
    }
}
