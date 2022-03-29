<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;

class GetMostPopularCPTCodes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cptcodes = Charge::get()->pluck('cptcode');
        $uniquecptcodes = $cptcodes->unique();

        $cptcodeswithcount = $cptcodes->countBy();
        dump($cptcodeswithcount->sort());
    }
}
