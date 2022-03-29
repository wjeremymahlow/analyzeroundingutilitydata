<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\ICD10Code;
use App\Models\Charge;
use App\Models\Date;
use App\Models\Attending;
use App\Models\Patient;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        if (ICD10Code::doesntExist()) $this->call([IcdCodesSeeder::class]);
        if (Charge::doesntExist()) $this->call([ChargesSeeder::class]);
        if (Date::doesntExist()) $this->call([DatesSeeder::class]);
        if (Attending::doesntExist()) $this->call([AttendingsSeeder::class]);
        if (Patient::doesntExist()) $this->call([PatientsSeeder::class]);
        
        $this->call([AssignChargesToAttendingDatePatientSeeder::class]);
        $this->call([AssignChargesToICD10Codes::class]);
    }
}
