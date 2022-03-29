<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\Date;
use App\Models\Attending;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RoundingNumbersForAttendings;

class GetRoundingNumbersForAttendings extends Seeder
{
    protected $attendings;
    protected $dates;
    protected $rows;
    protected $targetcptcodes;

    public function __construct()
    {
    	$this->attendings = Attending::whereIn('abbreviation',['C2', 'R2', 'J2', 'H2', 'S', 'L', 'G', 'V2', 'P', 'A3'])->get();
    	$this->dates = Date::where('id','>=',731)->where('id','<=',1096)->whereNotIn('dayofweek',['Saturday','Sunday'])->get();
    	$this->rows = collect();
        $this->targetcptcodes = collect([
            '23 hour obs discharge',
            '217',
            '223',
            '231',
            '232',
            '233',
            '238',
            '253',
            '254',
            '254.93458',
            '255',
            '254-GC',
            '254-gc',
            '9231GC',
            '92388; 99217',
            '9255GC',
            '92960 and outpatient consult lvl 4',
            '9922',
            '9932',
            '99208',
            '99214',
            '99215',
            '99217',
            '99217-GC',
            '99217; 92388',
            '99217GC',
            '99219',
            '99219GC',
            '99220',
            '99220-GC',
            '99220GC',
            '99221',
            '99222',
            '99222-GC',
            '99223',
            '99223 -S',
            '99223 S',
            '99223-25',
            '99223-35',
            '99223-GC',
            '99223-GC;',
            '99223-GC; 93458-59',
            '99223-S',
            '99223-gc',
            '99223GC',
            '99223GC93458GC',
            '99223NP',
            '99223S',
            '99223np',
            '99223s',
            '99225',
            '99225-gc',
            '99226',
            '99231',
            '99231-24',
            '99231-24-GC',
            '99231-25',
            '99231-GC',
            '99231-GC-24',
            '99231-gc',
            '99231GC',
            '99231GS',
            '99231gc',
            '99232',
            '99233',
            '99238',
            '99239',
            '99244',
            '99245',
            '99250',
            '99251',
            '99252',
            '99253',
            '99254',
            '923325',
            '99232 (see notes please)',
            '99232 -GC',
            '99232 tik',
            '99232-24',
            '99232-24-GC',
            '99232-25',
            '99232-GC',
            '99232-GC93460',
            '99232-gc',
            '99232. 93280-26',
            '992321',
            '992322',
            '99232GC',
            '99232gc',
            '99233 -GC',
            '99233-25',
            '99233-GC',
            '99233-gc',
            '992331',
            '992332',
            '99233; 99356',
            '99233GC',
            '99233S',
            '99233s',
            '99234-GC',
            '99235-GC',
            '99238-24',
            '99238-GC',
            '99238GC',
            '99239-24',
            '99239-25',
            '99239-GC',
            '99239-gc',
            '99239; ICD check dual',
            '99239GC',
            '99244-GC',
            '99244-GC-24',
            '99245-GC',
            '99245GC',
            '992524-GC',
            '99253-25',
            '99253-GC',
            '99253-S',
            '99253-gc',
            '99253GC',
            '99254 - GC',
            '99254 S',
            '99254--GC',
            '99254-25',
            '99254-GC',
            '99254-Gc',
            '99254-NP',
            '99254-S',
            '99254-gc',
            '99254. 93458',
            '992545GC',
            '99254; 99358',
            '99254GC',
            '99254NP',
            '99254S',
            '99254s',
            '99255',
            '99255 - GC',
            '99255 S',
            '99255 S-25',
            '99255- GC',
            '99255-25',
            '99255-57',
            '99255-GC',
            '99255-S',
            '99255-gc',
            '992555-GC',
            '99255GC',
            '99255S',
            '99255gc',
            '99255s',
            '99258-59',
            '9925GC',
            '99260',
            '99285',
            '99291',
            '99291 for 200 mts',
            '99291-gc',
            '99291; 99292',
            '99291and 99358',
            '99292',
            '99312',
            '99321',
            '99323',
            '99328',
            '99356',
            '99358',
            '998232GC',
            '999232',
            '999254-GC',
            'Bill fo outpatient level 5 consult',
            'Bill outpatient visit level 4',
            'DC',
            'DC 24 hours obs',
            'GC 99254',
            'GC99223',
            'GC99232',
            'GC99254',
            'GC99255',
            'GS 99232',
            'GS 99254',
            'GS99254',
            'NP 99255',
            'NP-99255',
            'S 99254',
            'S-99223',
            'S-99223-57',
            'S-99255',
            'S99220',
            'S99223',
            'S99223-25',
            'S99232',
            'S99233',
            'S99235',
            'S99238',
            'S99254',
            'S99254-GC',
            'S99255',
            'admit',
            'd',
            'dc',
            'dc 23 hour obs',
            'dc 24 hour obs',
            'dc cardioversion',
            'dc obs',
            'discharge - 23 hour obs',
            'discharge 23 hour obs',
            'discharge 23 hours obs',
            'discharge 23 hours obs; PPM programming single lead',
            'discharge OBS',
            'discharge obs',
            'np99223',
            'outpatient visit level 3',
            's99223',
        ]);
    }

    public function run()
    {
    	dump('Analyzing daily census data from ' . Date::find($this->dates->min('id'))->formatted_date . ' to ' . Date::find($this->dates->max('id'))->formatted_date);
    	dump('For attendings: ' . $this->attendings->pluck('abbreviation')->implode(','));
        dump('For cptcodes: ' . $this->targetcptcodes->implode(','));
        $this->attendings->each(function($attending) {
        	dump('Analyzing data for ' . $attending->abbreviation);
        	$dailycensuses = $this->dates->map(function($date) use ($attending) {

        	    $attendingschargesfordate = Charge::with('patient')->where('date_id',$date->id)->where('attending_id',$attending->id)->whereIn('cptcode',$this->targetcptcodes)->get();
                $attendingpatientsfordate = $attendingschargesfordate->pluck('patient')->unique('id');

        	    $dailycensus = $attendingpatientsfordate->count();

        	    echo $dailycensus . ',';

        	    // if ($dailycensus > 20) {
        	    // 	dump($attending->abbreviation);
        	    // 	dump($date->formatted_date);
        	    // 	dd($attendingpatientsfordate->pluck('name')->implode(','));
        	    // }

        	    return $dailycensus;       	    

        	});
        	echo PHP_EOL;

        	$this->rows->push([
        		'attending' => $attending->abbreviation,
        		'average daily census' => $dailycensuses->average(),
        		'minmum daily census' => $dailycensuses->min(),
        		'maximum daily census' => $dailycensuses->max(),
        	]);

        });

        // dd($this->rows);
    	return Excel::store(new RoundingNumbersForAttendings($this->rows), 'RoundingNumbersForAttendings.xlsx');
    }
}
