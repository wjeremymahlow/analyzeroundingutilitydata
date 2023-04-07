<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\Date;

class GetUniqueCharges extends Seeder
{
	protected $cptcodes;
    protected $initialdate;
    protected $finaldate;

	public function __construct()
	{
		$this->cptcodes = collect();
        $this->initialdate = Date::where('year',2022)->where('month',1)->where('day',1)->first();
        $this->finaldate = Date::where('year',2022)->where('month',12)->where('day',31)->first();
	}

    public function run()
    {

    	Charge::whereBetween('date_id',[$this->initialdate->id,$this->finaldate->id])->chunk(100, function($charges) {
    		$charges->each(function($charge) {
    			// $this->cptcodes->push($charge->formattedcptcode());
                $this->cptcodes->push($charge->cptcode);
    		});    		
    	});


        $this->cptcodes->unique()->sort()->each(function ($cptcode) {
        	echo "'" . $cptcode . "'," . PHP_EOL;
        });
    }
}
