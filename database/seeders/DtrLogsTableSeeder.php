<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class DtrLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeChoice = $this->command->choice(
            'Which employee(s) to create DTR logs?',
            array_merge(['All'], employeeLists()),
            0,// $defaultIndex,
            null, //$maxAttempts = null,
            true//$allowMultipleSelections = false
        );

        $this->command->info("You've chosen:\n". implode("\n", $employeeChoice));
        
        $r = currentDate().'/'.currentDate();
        $dateRange = $this->command->ask('Enter DTR logs date range FROM and TO:', $r);

        $shiftChoice = $this->command->choice(
            'Select shift schedule to base DTR logs?',
            collect(shiftScheduleLists())->flatten()->toArray()
        );
    }
}
