<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\Date;
use App\Models\Attending;

class GetMonthlyCPTCodesForAttendings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attending = Attending::where('abbreviation','V')->first();

        for ($i=1; $i <= 12; $i++) { 
        	$this->dumpresultstoscreen(2020,$i,$attending);
        }
        for ($i=1; $i <= 12; $i++) { 
        	$this->dumpresultstoscreen(2021,$i,$attending);
        }

    }

    protected function getattendingscptcodesformonth(int $year,int $month,Attending $attending)
    {
    	$dates = Date::where('year',$year)->where('month',$month)->get();
    	$charges = Charge::whereIn('date_id',$dates->pluck('id'))->where('attending_id',$attending->id)->get();
    	$charges->each(function($charge) {
    		if (strpos($charge->cptcode,'-')) {
    			$charge->cptcode = substr($charge->cptcode,0,strpos($charge->cptcode,'-'));
    		}
    	});
    	return $charges->sortBy('cptcode')->groupBy('cptcode');
    }

    protected function dumpresultstoscreen(int $year,int $month,Attending $attending)
    {
    	echo PHP_EOL;
    	echo PHP_EOL;
    	dump('Year: ' . $year . ' Month: ' . $month);
    	$this->getattendingscptcodesformonth($year,$month,$attending)->each(function($charges) {
    		dump($charges->first()->cptcode . ': ' . $charges->count());
    	});
    }
}
