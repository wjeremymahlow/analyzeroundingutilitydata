<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Date;
use Illuminate\Support\Carbon;

class DatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        dump('Seeding Dates...');
    	$startdt = Carbon::createSafe(2017,1,1,0,0,0);
    	$enddt = Carbon::createSafe(2029,1,1,0,0,0);

    	$dt = $startdt->copy();
    	
    	while ($dt->lte($enddt)) {
            
    		$date = new Date;
    		$date->year=$dt->year;
    		$date->month=$dt->month;
    		$date->day=$dt->day;
            $date->formatted_date = $dt->format('l, F jS Y');
            $date->formatted_date_short = $dt->format('D M j Y');
            $date->formatted_date_supershort = $dt->format('n/j');
            $date->formatted_date_short_no_year = $dt->format('D M j');
            $date->formatted_date_monthandday = $dt->format('F jS');
            $date->formatted_date_dayofmonth = $dt->format('jS');
            $date->dayofweek = $dt->format('l');

            $dt2 = Carbon::createSafe($dt->year,$dt->month,1,0,0,0);
            $countofdayOfWeek = 0;
            while ($dt2->lte($dt)) {
                if ($dt2->dayOfWeek == $dt->dayOfWeek) $countofdayOfWeek += 1;
                $dt2->addDay();
            }

            switch ($countofdayOfWeek) {
                case 1:
                    $formattedcountofdayOfWeek = '1st';
                    break;
                
                case 2:
                    $formattedcountofdayOfWeek = '2nd';
                    break;

                case 3:
                    $formattedcountofdayOfWeek = '3rd';
                    break;

                case 4:
                    $formattedcountofdayOfWeek = '4th';
                    break;

                case 5:
                    $formattedcountofdayOfWeek = '5th';
                    break;

                default:
                    exit('switch error: ' . $countofdayOfWeek . PHP_EOL);
                    break;
            }

            $date->formatted_date_for_monthlyonweekday = 'the ' . $formattedcountofdayOfWeek . ' ' . $date->dayofweek . ' of the month';
            $date->countofdayOfWeek = $countofdayOfWeek;

    		$date->save();

    		$dt->addDay();
    	}
    }
}
