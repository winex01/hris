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
        $r = currentDate();
        $dateFrom = $this->command->ask('Enter DTR logs date from:', $r);
        $dateTo = $this->command->ask('Enter DTR logs date to:', $r);

        $employees = employeeLists();
        $info = null;
        foreach ($employees as $empId => $empName) {
            $info .= "\n".$empId." => ".$empName;
        }
        
        $this->command->info($info);

        $r = 'all';
        $selectedEmp = $this->command->ask('Select employee(s) id(PK) to have DTR logs generated with comma delimiter:', $r);

        // TODO:: 
    }
}
