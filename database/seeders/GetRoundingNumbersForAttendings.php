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
    	// $this->attendings = Attending::whereIn('abbreviation',['CDV','KMM','SMP','KEA','MKM','LCP','JLJ','KMH','JRS','MJG','MDM',])->get();
        $this->attendings = Attending::whereIn('abbreviation',['MKM','KMM','CDV','JLJ','LCP'])->get();
        // MKM McCarley KMM Martini CDV Vance JLJ Jones LCP Parker
    	$this->dates = Date::where('id','>=',1827)->where('id','<=',2191)->get();
    	$this->rows = collect();
        $this->targetcptcodes = collect([
            '217',
            '220',
            '223',
            '223-gc',
            '223-s',
            '23 hour obs admit',
            '23 hours obs dc',
            '232',
            '232-gc',
            '232-s',
            '233',
            '238',
            '254',
            '245-gc',
            '254-GC',
            '254-S',
            '254-gc',
            '254-s',
            '291',
            '5',
            '99152',
            '99152 (at 1927PM)',
            '99152 see note',
            '99152-GC',
            '99153',
            '99207',
            '99208',
            '99214',
            '99215',
            '99217',
            '99217-GC',
            '99217-S',
            '99217-s',
            '99217GC',
            '99220',
            '99222',
            '99222-GC',
            '99223',
            '99223-24',
            '99223-25',
            '99223-25GC',
            '99223-57',
            '99223-57-gc',
            '99223-GC',
            '99223-gc',
            '99223GC',
            '99223GC-25',
            '99223S',
            '99223s',
            '99224-GC',
            '99225',
            '99226',
            '99231',
            '99231-15',
            '99231-25',
            '99231-GC',
            '99231-S',
            '99231C',
            '99231CG',
            '99231GC',
            '99231S',
            '99231s',
            '99232',
            '99232-',
            '99232- GC',
            '99232-24',
            '99232-25',
            '99232-25GC',
            '99232-GC',
            '99232-Gc',
            '99232-S',
            '99232-gc',
            '99232. PPM programming dual chamber',
            '992323GC',
            '99232GC',
            '99232GC-25',
            '99232GC; 99407',
            '99232S',
            '99232gc',
            '99232s',
            '99233',
            '99233-24',
            '99233-25',
            '99233-57',
            '99233-GC',
            '99233-S',
            '99233-gc',
            '99233-gc-57',
            '992332GC',
            '99233; 99291',
            '99233; 99291; 99292',
            '99233GC',
            '99233S',
            '99233s',
            '99236',
            '99238',
            '992353',
            '99238-GC',
            '99238-S',
            '99238GC',
            '99238S',
            '99239',
            '99239-24',
            '99239-25',
            '99239-GC',
            '99239-S',
            '99239-gc',
            '99239; BiV ICD programming',
            '99239GC',
            '99239S',
            '99239s',
            '9923GC',
            '99244',
            '99244-GC',
            '99245',
            '99245-57',
            '992454-GC',
            '99252',
            '99253',
            '99254',
            '992524',
            '99253-GC',
            '99253GC',
            '99254-25',
            '99254-GC',
            '99254-S',
            '99254-gc',
            '99254.93454',
            '99254GC',
            '99254GC-25',
            '99254S',
            '99254s',
            '99255',
            '99255 +',
            '99255-24',
            '99255-25',
            '99255-25GC',
            '99255-57',
            '99255-57-gc',
            '99255-CC',
            '99255-GC',
            '99255-GC (see note)',
            '99255-GC-57',
            '99255-S',
            '99255-gc',
            '99255-gc-57',
            '99255-gc.92960',
            '992555-GC',
            '99255GC',
            '99255GC-25',
            '99255S',
            '99255S-25',
            '99255s',
            '99259-GC',
            '99260',
            '99291',
            '99291-GC',
            '99291-gc',
            '99291; 99292',
            '99291; 99292. 99292',
            '99291; 99292; 99292',
            '99292',
            '99321',
            '99323-GC',
            '99354GC',
            '99543',
            '99555',
            '993320',
            '999233-GC',
            '999239',
            'AP99232',
            'AP99254',
            'APP 99232',
            'APP 992332',
            'APP 99239',
            'APP 99254',
            'APP9255',
            'APP99217',
            'APP99223',
            'APP99231',
            'APP99232',
            'APP992321',
            'APP99233',
            'APP99238',
            'APP99239',
            'APP9925',
            'APP99254',
            'APP99255',
            'APP99291',
            'APP9954',
            'APP9955',
            'APPP992255',
            'App99231',
            'App99255',
            'Consult Er',
            'GC99232',
            'GC99232-25',
            'GC99254',
            'S-99217',
            'S-99255',
            'S9232',
            'S99217',
            'S9922',
            'S99220',
            'S99222',
            'S99223',
            'S9923',
            'S99231',
            'S99232',
            'S99233',
            'S99238',
            'S99239',
            'S99254',
            'S99254-25',
            'S99255',
            'S99291',
            'aapp 99232',
            'admit',
            'app 99232',
            'app 99239',
            'app 99254',
            'app99231',
            'app99232',
            'app99254',
            'app99255',
            'appp 99232',
            'appp 99254',
            'concult',
            'consult',
            'dc',
            'dc 23 hour obs',
            'dc 23 hour obs; BiV ICD programming',
            'dc 23 hour obs; PPM programming dual chamber',
            'dc 23 hour obs; PPM programming multilead device',
            'dc 23 hour ons',
            'dc 23 hours',
            'dc 23 hours obs',
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

        	    $attendingschargesfordate = Charge::with('patient')->where('date_id',$date->id)->where('attending_id',$attending->id)->whereIn('cptcode',$this->targetcptcodes)->get()->filter(function($charge) {
                    if(str_ireplace('app', '', $charge->cptcode) == $charge->cptcode) return false;
                    return ($this->targetcptcodes->contains($charge->cptcode));
                });
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
