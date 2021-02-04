<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	$this->createSuperAdminAccount();

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(BloodTypesTableSeeder::class);
        $this->call(CivilStatusesTableSeeder::class);
        $this->call(GendersTableSeeder::class);
        $this->call(ReligionsTableSeeder::class);
        $this->call(CitizenshipsTableSeeder::class);
        $this->call(EducationalLevelsTableSeeder::class);
        $this->call(RelationsTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(PayBasesTableSeeder::class);
        $this->call(JobStatusesTableSeeder::class);
        $this->call(EmploymentStatusesTableSeeder::class);
        $this->call(DaysPerYearsTableSeeder::class);
        $this->call(EmploymentInfoFieldsTableSeeder::class);
        $this->call(AppraisalTypesTableSeeder::class);
        $this->call(AppraisalInterpretationsTableSeeder::class);
    }

    private function createSuperAdminAccount()
    {
        foreach (['admin', 'test'] as $name) {
            $email = "$name@$name.com";
            
            $check = User::where('email', $email)->first();

            if (!$check) {
                User::firstOrCreate([
                    'name' => ucwords($name),
                    'email' => $email,
                    'password' => bcrypt('password123'),
                ]);
            }

        }// end foreach
    
    }// end method
}
