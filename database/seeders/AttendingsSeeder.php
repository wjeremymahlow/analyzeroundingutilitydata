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
            'KMM' => 'Martini NP',
            'B2' => 'Dr. Bailey',
            'LCP' => 'L. Parker',
            'MKM' => 'McCarley NP',
            'W' => 'Dr. Wehber',
            'CDV' => 'Vance NP',
            'MJG' => 'Gentry NP',
            'GB' => 'Dr. Gayathri',
            'KMH' => 'Harvey NP',
            'JENNINGS' => 'Dr. Jennings',
            'LIU' => 'Dr. Liu',
            'SHEPPLE' => 'Dr. Shepple',
            'JENNINGS' => 'Dr. Jennings',
            'C' => 'Dr. Scott',
            'PERKEL' => 'Dr. Perkel',
            'LGM2' => 'Unknown',
            'KEA' => 'Kristin Anklowitz NP',
            'LAD' => 'Lisa Dugger NP',
            'S2' => 'Dr. Shaikh',
            'I' => 'Dr. Isang',
            'M4' => 'Dr. McBride',
            'JLJ' => 'Julia Jones NP',
            'LD' => 'Lisa Dugger 2 NP',
            'LEM' => 'Lauren Monroe NP',
            'SMP' => 'Matt Parker NP',
            'FSW' => 'Felicia Wassenger NP',
            'FSS' => 'Felicia Schaap NP',
            
    	]);
    }

    public function run()
    {
    	dump('Seeding Attendings...');

        Charge::chunk(500, function($charges) {
        	$charges->each(function($charge) {
	        	$this->abbreviations->push(strtoupper($charge->billingmdabbreviation));
        	});
            echo '.';
        });

        $uniqueabbreviations = $this->abbreviations->unique();
        $uniqueabbreviations->each(function($abbreviation) {
            if (isset($this->namesfromabbreviation[strtoupper($abbreviation)])) {
                $attending = new Attending;
                $attending->abbreviation = $abbreviation;
                $attending->name = $this->namesfromabbreviation[$abbreviation];
                $npabbreviations = collect(['MDM','JRS','HS','FSS','KEA','LAD','JLJ','LEM','SMP','FSW','FSS']);
                if ($npabbreviations->contains($abbreviation)) $attending->role = 'nursepracticioner';
                else $attending->role = 'physician';
                $attending->save();
            } else {
                dump('I do not know the abbreviation ' . $abbreviation);
            }
        });
        
    }
}
