<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\ICD10Code;


class AssignChargesToICD10Codes extends Seeder
{

    public function run()
    {
    	dump('Assigning Charges to ICD10 Codes...');

        Charge::chunkById(500, function($charges) {
        	$charges->each(function($charge) {
        		if ($charge->icd10code1) {
        			$icd10code = ICD10Code::where('code',strtoupper($charge->icd10code1))->first();
        			if ($icd10code) {
        				$charge->icd10codes()->attach($icd10code->id);
        			} else {
                        $charge->error = true;
                        $charge->errormessage = 'ICD10 Code Not Found';
                        $charge->save();
                        $this->makemanualICD10Code($charge, $charge->icd10code1);
        			}
        		}
        		if ($charge->icd10code2) {
        			$icd10code = ICD10Code::where('code',strtoupper($charge->icd10code2))->first();
        			if ($icd10code) {
        				$charge->icd10codes()->attach($icd10code->id);
        			} else {
                        $charge->error = true;
                        $charge->errormessage = 'ICD10 Code Not Found';
                        $charge->save();
                        $this->makemanualICD10Code($charge, $charge->icd10code2);
        			}
        		}
        		if ($charge->icd10code3) {
        			$icd10code = ICD10Code::where('code',strtoupper($charge->icd10code3))->first();
        			if ($icd10code) {
        				$charge->icd10codes()->attach($icd10code->id);
        			} else {
                        $charge->error = true;
                        $charge->errormessage = 'ICD10 Code Not Found';
                        $charge->save();
                        $this->makemanualICD10Code($charge, $charge->icd10code3);
        			}
        		}
        		if ($charge->icd10code4) {
        			$icd10code = ICD10Code::where('code',strtoupper($charge->icd10code4))->first();
        			if ($icd10code) {
        				$charge->icd10codes()->attach($icd10code->id);
        			} else {
                        $charge->error = true;
                        $charge->errormessage = 'ICD10 Code Not Found';
                        $charge->save();
                        $this->makemanualICD10Code($charge, $charge->icd10code4);
        			}
        		}
        	});
        	echo '.';
        });

        dump(' ');
        dump('Charges with errors:');
        dump(Charge::where('error',true)->count());
    }

    protected function makemanualICD10Code(Charge $charge, string $code)
    {
        $icd10code = new ICD10Code;
        $icd10code->code = strtoupper($code);
        $icd10code->description = $code;
        $icd10code->manuallyentered = true;
        $icd10code->save();

        $charge->icd10codes()->attach($icd10code->id);

    }
}
