<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\Patient;

class PatientsSeeder extends Seeder
{
    protected $powerchartmrns;

    public function __construct()
    {
    	$this->powerchartmrns = collect();
    }

    public function run()
    {
    	dump('Seeding Patients...');

        Charge::chunk(1000, function($charges) {
        	$charges->each(function($charge) {
	        	$this->powerchartmrns->push($charge->powerchartmrn);
        	});
            echo '.';
        });

        echo PHP_EOL;
        echo 'Creating patients...';
        $uniquepowerchartmrns = $this->powerchartmrns->unique();
        $uniquepowerchartmrns->each(function($powerchartmrn) {
            $charges = Charge::where('powerchartmrn',$powerchartmrn)->get();
            $chargeforcpsmrn = $charges->where('cpsmrn','!=',null)->first();
            $cpsmrn = $chargeforcpsmrn ? $chargeforcpsmrn->cpsmrn : null;
            $chargeforname = $charges->where('patientname','!=',null)->first();
            $name = $chargeforname ? $chargeforname->patientname : null;

            $patient = new Patient;
            $patient->powerchartmrn = $powerchartmrn;
            $patient->cpsmrn = $cpsmrn;
            $patient->name = $name;
            $patient->save();
            echo '.';
        });
        echo PHP_EOL;
    }
}
