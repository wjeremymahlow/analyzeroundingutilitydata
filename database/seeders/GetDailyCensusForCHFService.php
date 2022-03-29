<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Charge;
use App\Models\Date;
use App\Models\Attending;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendingCensusForDate;

class GetDailyCensusForCHFService extends Seeder
{
    protected $attending;
    protected $charges;
    protected $dailycensuses;
    protected $dailynumberofcharges;
    protected $rows;
    protected $targetcptcodes;

    public function __construct()
    {
    	// $this->attending = Attending::where('abbreviation','M3')->first();
    	// $this->charges = $this->attending->charges()->with('patient')->get();
    	$this->dailycensuses = collect();
    	$this->dailynumberofcharges = collect();
    	$this->rows = collect();
        $this->targetcptcodes = [
            
        ];
    }

    public function run()
    {
    	$initialdate = Date::where('year',2020)->where('month',1)->where('day',1)->first();
    	$finaldate = Date::where('year',2021)->where('month',1)->where('day',1)->first();
        Date::where('id','>',$initialdate->id)->where('id','<',$finaldate->id)->chunk(100, function($dates) {
            $dates->each(function($date) {
            	dump($date->formatted_date_short);

                $attendingschargesfordate = Charge::where('date_id',$date->id)->get()->filter(function($charge) {
                	$roundingmdabbreviations = collect(explode('/',$charge->roundingmdabbreviations));
                	return ($roundingmdabbreviations->contains('CHF') || $roundingmdabbreviations->contains('M3'));
                });
                if ($attendingschargesfordate->isNotEmpty()) dump($attendingschargesfordate->pluck('patientname'));
                $attendingpatientsfordate = $attendingschargesfordate->pluck('patient')->unique('id');

                $this->rows->push([
                	'date' => $date->formatted_date_short,
                	'attending' => 'CHF',
                	'number of patients billed' => $attendingpatientsfordate->count(),
                	'number of charges billed' => $attendingschargesfordate->count(),
                ]);

                // $this->dailycensuses->push($attendingpatientsfordate->count());
                // $this->dailynumberofcharges->push($attendingschargesfordate->count());
                
                // dump($attendingpatientsfordate->count());
            });
        });
        // dump('Average daily census is ' . $this->dailycensuses->avg());
        // dump('Average daily number of charges is ' . $this->dailynumberofcharges->avg());

        return Excel::store(new AttendingCensusForDate($this->rows), 'AttendingCensusForDate.xlsx');
    }
}
