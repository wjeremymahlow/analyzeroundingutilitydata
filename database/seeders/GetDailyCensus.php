<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\Date;
use App\Models\Attending;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CVICUPatientsWithCriticalCareTimeBilled;
use App\Exports\AttendingCensusForDate2;

class GetDailyCensus extends Seeder
{
    protected $dailynumberofcharges;
    protected $initialdate;
    protected $finaldate;
    protected $allcptcodes;
    protected $newadmitconsultcptcodes;
    protected $chfattendinginitials;
    protected $chfattendinginitialsrounding;
    protected $epattendinginitials;
    protected $allattendinginitials;
    protected $attendings;

    protected $countsofallpatientsfordate;
    protected $countsofcvicupatientsfordate;
    protected $countsofmccpatientsfordate;
    protected $countsoftsicupatientsfordate;
    protected $countsofnccpatientsfordate;
    protected $countsofcvrpatientsfordate;
    protected $countsofnoncvicumcctsicuncccvrpatientsfordate;
    protected $countsofallnewadmitsandconsultsfordate;
    protected $countsofepservicepatientsfordate;
    protected $countsofepservicenewadmitandconsultchargesfordate;
    protected $countsofchfservicepatientsfordate;
    protected $countsofchfservicenewadmitandconsultchargesfordate;

    protected $allcvicucptcodes;
    protected $cvicupatientswithcriticalcaretimebilleddata;

    public function __construct()
    {
        $this->countsofallpatientsfordate = collect();
        $this->countsofcvicupatientsfordate = collect();
        $this->countsofmccpatientsfordate = collect();
        $this->countsoftsicupatientsfordate = collect();
        $this->countsofnccpatientsfordate = collect();
        $this->countsofcvrpatientsfordate = collect();
        $this->countsofnoncvicumcctsicuncccvrpatientsfordate = collect();
        $this->countsofallnewadmitsandconsultsfordate = collect();
        $this->countsofepservicepatientsfordate = collect();
        $this->countsofepservicenewadmitandconsultchargesfordate = collect();
        $this->countsofchfservicepatientsfordate = collect();
        $this->countsofchfservicenewadmitandconsultchargesfordate = collect();
        $this->allcvicucptcodes = collect();
        $this->cvicupatientswithcriticalcaretimebilleddata = collect();
        $this->dailynumberofcharges = collect();
        $this->initialdate = Date::where('year',2021)->where('month',1)->where('day',1)->first();
        $this->finaldate = Date::where('year',2021)->where('month',2)->where('day',28)->first();
        $this->allcptcodes = collect([
            '223',
            '231',
            '232',
            '233',
            '238',
            '254',
            '99152',
            '99215',
            '99217',
            '99220',
            '99221',
            '99222',
            '99223',
            '99225',
            '99226',
            '99231',
            '99232',
            '99233',
            '99235',
            '99238',
            '99239',
            '99244',
            '99245',
            '99253',
            '99254',
            '99255',
            '99291',
            '99292',
            '99356',
            '9921724',
            '9922325',
            '9923125',
            '9923224',
            '9923225',
            '9923325',
            '9923924',
            '9925425',
            '9925457',
            '9925525',
            '9925557',
            '99255525',
            '9922392941',
            '9923399356',
            '9929199292',
            '992339935699357',
            '992919929299292',
            '9929199292992929929299292',
        ]);
        $this->newadmitconsultcptcodes = collect([
            '223',
            '254',
            '99215',
            '99220',
            '99221',
            '99222',
            '99223',
            '99244',
            '99245',
            '99253',
            '99254',
            '99255',
            '9922325',
            '9925425',
            '9925457',
            '9925525',
            '9925557',
            '99255525',
            '9922392941',
            '9923399356',
        ]);
        $this->chfattendinginitials = collect([
            'CHF','M3','H3',
        ]);
        $this->chfattendinginitialsrounding = collect([
            'CHF','M3',
        ]);
        $this->epattendinginitials = collect([
            'M','J','H',
        ]);
        $this->allattendinginitials = collect([
            'all',
        ]);
        $this->attendings = Attending::get();
        $this->attendings->each(function($attending) {
            $attending->countsofallpatientsfordate = collect();
        });
    }

    

    public function run()
    {
        Date::whereBetween('id',[$this->initialdate->id,$this->finaldate->id])->with('charges.patient')->chunk(100, function($dates) {
            $dates->each(function($date) {

                $unfilteredchargesfordate = $date->charges()->get();

                $allchargesfordate = $unfilteredchargesfordate->filter(function($charge) {
                    return ($this->allcptcodes->contains($charge->formattedcptcode()) || substr($charge, 0,2) == '99');
                });
                $newadmitandconsultchargesfordate = $unfilteredchargesfordate->filter(function($charge) {
                    return $this->newadmitconsultcptcodes->contains($charge->formattedcptcode());
                });

                $allpatientsfordate = $allchargesfordate->pluck('patient')->unique('id');

                $cvicupatientsfordate = $allchargesfordate->filter(function ($charge) {
                    return $charge->isincvicu();
                })->pluck('patient')->unique('id');

                // KayLeigh's request:
                $cvicupatients = $allchargesfordate->filter(function ($charge) {
                    return $charge->isincvicu();
                })->pluck('patient')->unique('id');
                $cvicucptcodes = $allchargesfordate->filter(function ($charge) {
                    return $charge->isincvicu();
                })->map(function($charge) {
                    return $charge->cptcode;
                });
                // dump($date->formatted_date . ': ' . $cvicucptcodes->implode(','));
                $cvicucptcodes->each(function($cptcode) {
                    $this->allcvicucptcodes->push($cptcode);
                });
                $cvicupatientswithcriticalcaretimebilled = $cvicupatients->filter(function($patient) use ($allchargesfordate) {
                    $chargesforthispatient = $allchargesfordate->where('patient_id',$patient->id);
                    $criticalcarechargesforthispatient = $chargesforthispatient->filter(function($charge) {
                        return Str::contains($charge->cptcode,['291']);
                    });
                    return $criticalcarechargesforthispatient->isNotEmpty();
                });
                // dump($date->formatted_date . ': ' . $cvicupatientswithcriticalcaretimebilled->count() . ' out of ' . $cvicupatients->count());
                $this->cvicupatientswithcriticalcaretimebilleddata->push([
                    $date->formatted_date,
                    $cvicupatients->count(),
                    $cvicupatientswithcriticalcaretimebilled->count(),
                ]);


                $mccpatientsfordate = $allchargesfordate->filter(function ($charge) {
                    return $charge->isinmcc();
                })->pluck('patient')->unique('id');
                $tsicupatientsfordate = $allchargesfordate->filter(function ($charge) {
                    return $charge->isintsicu();
                })->pluck('patient')->unique('id');
                $nccpatientsfordate = $allchargesfordate->filter(function ($charge) {
                    return $charge->isinncc();
                })->pluck('patient')->unique('id');
                $cvrpatientsfordate = $allchargesfordate->filter(function ($charge) {
                    return $charge->isincvr();
                })->pluck('patient')->unique('id');
                $noncvicumcctsicuncccvrpatientsfordate = $allchargesfordate->filter(function ($charge) {
                    return $charge->isinnoncvicumcctsicuncccvr();
                })->pluck('patient')->unique('id');
                
                $allnewadmitsandconsultsfordate = $newadmitandconsultchargesfordate->pluck('patient')->unique('id');

                $epservicepatientsfordate = $allchargesfordate->filter(function ($charge) {
                    return ($this->epattendinginitials->contains($charge->billingmdabbreviation));
                })->pluck('patient')->unique('id');
                $epservicenewadmitandconsultchargesfordate = $newadmitandconsultchargesfordate->filter(function ($charge) {
                    return ($this->epattendinginitials->contains($charge->billingmdabbreviation));
                })->pluck('patient')->unique('id');

                $chfservicepatientsfordate = $allchargesfordate->filter(function ($charge) {
                    return ($this->chfattendinginitials->contains($charge->billingmdabbreviation) && $this->chfattendinginitialsrounding->intersect($charge->roundingmdabbreviations())->isNotEmpty());
                })->pluck('patient')->unique('id');
                $chfservicenewadmitandconsultchargesfordate = $newadmitandconsultchargesfordate->filter(function ($charge) {
                    return ($this->chfattendinginitials->contains($charge->billingmdabbreviation) && $this->chfattendinginitialsrounding->intersect($charge->roundingmdabbreviations())->isNotEmpty());
                })->pluck('patient')->unique('id');

                $this->countsofallpatientsfordate->push($allpatientsfordate->count());
                if ($date->dayofweek != 'Saturday' && $date->dayofweek != 'Sunday') {
                    $this->attendings->each(function($attending) use($allchargesfordate) {
                        $attendingscharges = $allchargesfordate->filter(function($charge) use($attending) {
                            return ($charge->attending_id == $attending->id);
                        });
                        $countofpatients = $attendingscharges->pluck('patient')->unique('id')->count();
                        $attending->countsofallpatientsfordate->push($countofpatients);

                        // if ($attending->abbreviation == 'K' && $countofpatients == 24) dd($attendingscharges->pluck('cptcode'));
                    });
                }
                
                $this->countsofcvicupatientsfordate->push($cvicupatientsfordate->count());
                $this->countsofmccpatientsfordate->push($mccpatientsfordate->count());
                $this->countsoftsicupatientsfordate->push($tsicupatientsfordate->count());
                $this->countsofnccpatientsfordate->push($nccpatientsfordate->count());
                $this->countsofcvrpatientsfordate->push($cvrpatientsfordate->count());
                $this->countsofnoncvicumcctsicuncccvrpatientsfordate->push($noncvicumcctsicuncccvrpatientsfordate->count());

                $this->countsofallnewadmitsandconsultsfordate->push($allnewadmitsandconsultsfordate->count());
                if ($date->dayofweek != 'Saturday' && $date->dayofweek != 'Sunday') {
                    $this->countsofepservicepatientsfordate->push($epservicepatientsfordate->count());
                    $this->countsofepservicenewadmitandconsultchargesfordate->push($epservicenewadmitandconsultchargesfordate->count());
                    $this->countsofchfservicepatientsfordate->push($chfservicepatientsfordate->count());
                    $this->countsofchfservicenewadmitandconsultchargesfordate->push($chfservicenewadmitandconsultchargesfordate->count());
                }
                


            });
        });

        dump(' ');

        dump('Average daily census for entire cardiology service is ' . round($this->countsofallpatientsfordate->avg(),1) . ', range: ' . $this->countsofallpatientsfordate->min() . ' - ' . $this->countsofallpatientsfordate->max());
        dump('Average new admits/consults for entire cardiology service is ' . round($this->countsofallnewadmitsandconsultsfordate->avg(),1) . ', range: ' . $this->countsofallnewadmitsandconsultsfordate->min() . ' - ' . $this->countsofallnewadmitsandconsultsfordate->max());

        $rows = collect();
        $this->attendings->each(function($attending) use($rows) {
            dump('Average daily census for ' . $attending->abbreviation . ' is ' . round($attending->countsofallpatientsfordate->avg(),1) . ', range: ' . $attending->countsofallpatientsfordate->min() . ' - ' . $attending->countsofallpatientsfordate->max());
            $rows->push([
                $attending->abbreviation,
                round($attending->countsofallpatientsfordate->avg(),1),
                $attending->countsofallpatientsfordate->min(),
                $attending->countsofallpatientsfordate->max(),
            ]);
        });
        Excel::store(new AttendingCensusForDate2($rows), 'AttendingCensusForDate2.xlsx');

        dump('Average daily census for CVICU patients is ' . round($this->countsofcvicupatientsfordate->avg(),1) . ', range: ' . $this->countsofcvicupatientsfordate->min() . ' - ' . $this->countsofcvicupatientsfordate->max());
        dump('Average daily census for MCC patients is ' . round($this->countsofmccpatientsfordate->avg(),1) . ', range: ' . $this->countsofmccpatientsfordate->min() . ' - ' . $this->countsofmccpatientsfordate->max());
        dump('Average daily census for TSICU patients is ' . round($this->countsoftsicupatientsfordate->avg(),1) . ', range: ' . $this->countsoftsicupatientsfordate->min() . ' - ' . $this->countsoftsicupatientsfordate->max());
        dump('Average daily census for NCC patients is ' . round($this->countsofnccpatientsfordate->avg(),1) . ', range: ' . $this->countsofnccpatientsfordate->min() . ' - ' . $this->countsofnccpatientsfordate->max());
        dump('Average daily census for CVR patients is ' . round($this->countsofcvrpatientsfordate->avg(),1) . ', range: ' . $this->countsofcvrpatientsfordate->min() . ' - ' . $this->countsofcvrpatientsfordate->max());
        dump('Average daily census for ALL OTHER FLOORS patients is ' . round($this->countsofnoncvicumcctsicuncccvrpatientsfordate->avg(),1) . ', range: ' . $this->countsofnoncvicumcctsicuncccvrpatientsfordate->min() . ' - ' . $this->countsofnoncvicumcctsicuncccvrpatientsfordate->max());

        dump('Average daily census for EP service is ' . round($this->countsofepservicepatientsfordate->avg(),1) . ', range: ' . $this->countsofepservicepatientsfordate->min() . ' - ' . $this->countsofepservicepatientsfordate->max());
        dump('Average new admits/consults for EP service is ' . round($this->countsofepservicenewadmitandconsultchargesfordate->avg(),1) . ', range: ' . $this->countsofepservicenewadmitandconsultchargesfordate->min() . ' - ' . $this->countsofepservicenewadmitandconsultchargesfordate->max());

        dump('Average daily census for CHF service is ' . round($this->countsofchfservicepatientsfordate->avg(),1) . ', range: ' . $this->countsofchfservicepatientsfordate->min() . ' - ' . $this->countsofchfservicepatientsfordate->max());
        dump('Average new admits/consults for CHF service is ' . round($this->countsofchfservicenewadmitandconsultchargesfordate->avg(),1) . ', range: ' . $this->countsofchfservicenewadmitandconsultchargesfordate->min() . ' - ' . $this->countsofchfservicenewadmitandconsultchargesfordate->max());

        $filteredcvicucptcodes = $this->allcvicucptcodes->filter(function($cptcode) {
            return Str::contains($cptcode,['291']);
        });
        dump('There were a total of ' . $this->allcvicucptcodes->count() . ' charges in the cvicu and ' . $filteredcvicucptcodes->count() . ' critical care charges');

        Excel::store(new CVICUPatientsWithCriticalCareTimeBilled($this->cvicupatientswithcriticalcaretimebilleddata), 'CVICUPatientsWithCriticalCareTimeBilled.xlsx');

        
    }
}
