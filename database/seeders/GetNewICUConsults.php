<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\Date;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NewCVICUPatients;
use App\Exports\AllBilledICD10CodesExport;
use App\Exports\DateAndNumberOfNewCVICUPatients;
use Carbon\Carbon;

class GetNewICUConsults extends Seeder
{
	protected $cvicucharges;
	protected $individualcvicucharges;
    protected $rows;
    protected $initialdate;
    protected $finaldate;

	public function __construct()
	{
		$this->cvicucharges = collect();
		$this->individualcvicucharges = collect();
        $this->rows = collect();
        $this->initialdate = Date::where('year',2020)->where('month',9)->where('day',1)->first();
        $this->finaldate = Date::where('year',2020)->where('month',12)->where('day',31)->first();
	}

    public function run()
    {
        echo PHP_EOL;

        Charge::whereBetween('date_id',[$this->initialdate->id,$this->finaldate->id])->chunk(100, function($charges) {
            echo '.';
        	$charges->each(function($charge) {
                if ($charge->isincvicu()) $this->cvicucharges->push($charge);
        	});
        });

        echo PHP_EOL;
        echo PHP_EOL;

        $this->cvicucharges->each(function($cvicucharge) {
        	$chargesforsamevisit = $this->individualcvicucharges->where('patient_id',$cvicucharge->patient_id)->filter(function($individualcvicucharge) use($cvicucharge) {
        		$datedifference = abs($individualcvicucharge->date_id - $cvicucharge->date_id);
        		return ($datedifference < 21);
        	});
        	if ($chargesforsamevisit->isEmpty()) {
        		$this->individualcvicucharges->push($cvicucharge);
                echo '.';
        	}
        });

        $this->individualcvicucharges->each(function($charge) {
            $this->rows->push([
                'dateofservice' => $charge->dateofservice,
                'patientname' => $charge->patientname,
                'room' => $charge->room,
                'powerchartmrn' => $charge->powerchartmrn,
                'billingmdabbreviation' => $charge->billingmdabbreviation,
            ]);
        });

        echo PHP_EOL;
        echo PHP_EOL;

        dump($this->individualcvicucharges->count() . ' charges found.');
        dump($this->individualcvicucharges->unique('patient_id')->count() . ' charges on unique patients found.');


        Excel::store(new NewCVICUPatients($this->rows), 'NewCVICUPatients.xlsx');

        $DateAndNumberOfNewCVICUPatientsrows = Date::orderBy('id')->get()->map(function($date) {
            $dt = Carbon::create($date->year,$date->month,$date->day,0,0,0);
            return [
                'date' => $dt->toDateString(),
                'numberofnewCVICUpatients' => $this->individualcvicucharges->where('date_id',$date->id)->count(),
            ];
        });
        Excel::store(new DateAndNumberOfNewCVICUPatients($DateAndNumberOfNewCVICUPatientsrows), 'DateAndNumberOfNewCVICUPatients.xlsx');


        
    }
}
