<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\Attending;

class AttendingsSeeder extends Seeder
{
    protected $abbreviations;
    protected $namesfromabbreviation;

    public function __construct()
    {
    	$this->abbreviations = collect();

    	$this->namesfromabbreviation = collect([
    		'C2' => 'Dr. Crook',
    		'R' => 'Dr. Raj',
    		'MDM' => 'Marissa Monroe NP',
    		'G' => 'Dr. Gayathri',
    		'M3' => 'Dr. Mehmood',
    		'O' => 'Dr. Overly',
    		'M' => 'Dr. Mahlow',
    		'K' => 'Dr. Litton',
    		'H' => 'Dr. Hirsh',
    		'S' => 'Dr. Shepple',
    		'D' => 'Dr. Dolacky',
    		'L' => 'Dr. Liu',
    		'V' => 'Dr. Venero',
    		'J2' => 'Dr. Johnson',
    		'M2' => 'Dr. Chua',
    		'R2' => 'Dr. Rogers',
    		'B' => 'Dr. Bresee',
    		'J' => 'Dr. Cox',
    		'H2' => 'Dr. Huntsinger',
    		'JRS' => 'Jen Sexton NP',
    		'P' => 'Dr. Perkel',
    		'A3' => 'Dr. Acker',
    		'HS' => 'Hope Sellars NP',
            'V2' => 'Dr. Villarosa',
            'H3' => 'Dr. Hirst',
            'K2' => 'Dr. Litton',
            'CHF' => 'CHF',
    	]);
    }

    public function run()
    {
    	dump('Seeding Attendings...');

        Charge::chunk(500, function($charges) {
        	$charges->each(function($charge) {
	        	$this->abbreviations->push($charge->billingmdabbreviation);
        	});
            echo '.';
        });

        $uniqueabbreviations = $this->abbreviations->unique();
        $uniqueabbreviations->each(function($abbreviation) {
            if (isset($this->namesfromabbreviation[$abbreviation])) {
                $attending = new Attending;
                $attending->abbreviation = $abbreviation;
                $attending->name = $this->namesfromabbreviation[$abbreviation];
                if ($abbreviation == 'MDM' || $abbreviation == 'JRS' || $abbreviation == 'HS') $attending->role = 'nursepracticioner';
                else $attending->role = 'physician';
                $attending->save();
            }
        });
        
    }
}
