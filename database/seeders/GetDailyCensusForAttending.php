<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\Date;
use App\Models\Attending;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendingCensusForDate;

class GetDailyCensusForAttending extends Seeder
{
    protected $attending;
    protected $charges;
    protected $dailycensuses;
    protected $dailynumberofcharges;
    protected $rows;
    protected $targetcptcodes;


    public function __construct()
    {
    	$this->attending = Attending::where('abbreviation','M3')->first();
    	$this->charges = $this->attending->charges()->with('patient')->get();
    	$this->dailycensuses = collect();
    	$this->dailynumberofcharges = collect();
    	$this->rows = collect();
        $this->targetcptcodes = [
            
        ];
    }

    public function run()
    {
        Date::where('id','>=',250)->where('id','<=',640)->chunk(100, function($dates) {
            $dates->each(function($date) {

                $attendingschargesfordate = $this->charges->where('date_id',$date->id);
                $attendingpatientsfordate = $attendingschargesfordate->pluck('patient')->unique('id');

                $this->rows->push([
                	'date' => $date->formatted_date_short,
                	'attending' => $this->attending->abbreviation,
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
