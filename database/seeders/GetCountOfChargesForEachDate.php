<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\Date;
use App\Exports\DateAndCountOfCharges;

class GetCountOfChargesForEachDate extends Seeder
{
    protected $rows;
    protected $cptcodes;

    public function __construct()
    {
        $this->rows = collect();
        $this->cptcodes = collect();
        $this->targetcodearray = [
        	'223',
        	'254',
        	'254-GC',
        	'254-gc',
        	'99220',
        	'99220-GC',
        	'99223',
        	'99223-25',
        	'99223-35',
        	'99223-GC',
        	'99223-S',
        	'99223-gc',
        	'99223GC',
        	'99223NP',
        	'99223S',
        	'99223s',
        	'99253',
        	'99253-GC',
        	'99253-gc',
        	'99253GC',
        	'99254',
        	'99254-25',
        	'99254-GC',
        	'99254-NP',
        	'99254-gc',
        	'99254GC',
        	'99254S',
        	'99255',
        	'99255 S',
        	'99255 S-25',
        	'99255-25',
        	'99255-GC',
        	'99255-S',
        	'99255-gc',
        	'99255GC',
        	'99255S',
        	'99255s',
        	'9925GC',
        	'S-99255',
        	'S99223',
        	'S99223-25',
        	'S99254',
        	'S99255',
        	'np99223',
        ];
    }

    public function run()
    {
        Date::whereIn('dayofweek',['Saturday','Sunday'])->with('charges.patient')->chunk(100, function($dates) {
            $dates->each(function($date) {

            	$chargesfordate = $date->charges->where('billingmdabbreviation','M')->whereIn('cptcode',$this->targetcodearray);

            	$this->rows->push([
            		'Date' => $date->formatted_date_short,
            		'Number of Charges Billed' => $chargesfordate->count(),
            		'Number of Patients Billed' => $chargesfordate->pluck('patient')->unique('id')->count(),
            	]);

            });
        });

        // echo PHP_EOL;
        // echo PHP_EOL;
        // $this->cptcodes->unique()->sort()->each(function($cptcode) {
        // 	echo "'" . $cptcode . "'," . PHP_EOL;
        // });
        // echo PHP_EOL;
        // echo PHP_EOL;

        return Excel::store(new DateAndCountOfCharges($this->rows), 'DateAndCountOfCharges.xlsx');
    }
}
