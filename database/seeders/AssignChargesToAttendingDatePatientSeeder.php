<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\Date;
use App\Models\Attending;
use App\Models\Patient;
use Carbon\Carbon;

class AssignChargesToAttendingDatePatientSeeder extends Seeder
{
	protected $attendingsgroupedbyabbreviation;
    protected $patientsgroupedbypowerchartmrn;

	public function __construct()
	{
		$this->attendingsgroupedbyabbreviation = collect();
        $this->patientsgroupedbypowerchartmrn = collect();
	}

    public function run()
    {
    	dump('Assigning Charges...');

        $this->attendingsgroupedbyabbreviation = Attending::get()->groupBy('abbreviation');
        $this->patientsgroupedbypowerchartmrn = Patient::get()->groupBy('powerchartmrn');

        Charge::chunkById(500, function($charges) {
        	$charges->each(function($charge) {
                if (strtotime($charge->dateofservice)) {
                    $charge->error = false;
                    $charge->errormessage = null;
                    if (isset($this->attendingsgroupedbyabbreviation[$charge->billingmdabbreviation]) && isset($this->patientsgroupedbypowerchartmrn[$charge->powerchartmrn])) {
                        $attending = $this->attendingsgroupedbyabbreviation[$charge->billingmdabbreviation]->first();
                        $charge->attending_id = $attending->id;

                        $dt = new Carbon($charge->dateofservice);
                        $date = Date::where('year',$dt->year)->where('month',$dt->month)->where('day',$dt->day)->firstOrFail();
                        $charge->date_id = $date->id;

                        $patient = $this->patientsgroupedbypowerchartmrn[$charge->powerchartmrn]->first();
                        $charge->patient_id = $patient->id;
                    } else {
                        $charge->error = true;
                        $charge->errormessage = 'Attending Not Found';
                    }
                    $charge->save();
                } else {
                    dump('Invalid date on this charge:');
                    dump($charge);
                    dd('Please format the excel column as string not date');
                }
        	});
            echo '.';
        });

        dump(' ');
        dump('Charges with errors:');
        dump(Charge::where('error',true)->count());
    }
}
