<?php

namespace App\Console\Commands;

use App\Models\EmploymentInformation;
use Illuminate\Console\Command;

class GenerateEmploymentInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'winex:make-employmentinfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate faker employment information data.';

    private $faker;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $employeeLists = employeeLists();
        $employeeChoice = $this->choice(
            'Which employee(s) to create DTR logs?',
            array_merge(['All'], $employeeLists),
            0,// $defaultIndex,
            null, //$maxAttempts = null,
            true//$allowMultipleSelections = false
        );

        $this->info("You've chosen:\n". implode("\n", $employeeChoice));

        // if `All` is selected then disregard others and select all employees instead
        if (in_array("All", $employeeChoice)) {
            $employeeChoice = $employeeLists;
        }

        $this->getOutput()->progressStart(count($employeeChoice));
        foreach ($employeeChoice as $employee) {
            $employeeId = modelInstance('Employee')
                ->where('badge_id', getStringBetweenParenthesis($employee))
                ->pluck('id')
                ->first();

            $empInfoFields = classInstance('EmploymentInfoField')->pluck('field_type', 'name')->toArray();


            foreach ($empInfoFields as $fieldName => $type) {
                if ($type == 1) { //select ID
                    $class = strtolower($fieldName);
                    $class = convertToClassName($class);
                
                    $id = modelInstance($class)::select('id')->inRandomOrder()->first()->id;

                    modelInstance('EmploymentInformation')->create([
                        'employee_id' => $employeeId,
                        'field_name' => $fieldName,
                        'field_value' => '{ "id": "'.$id.'" }',
                        'effectivity_date' => currentDate(),
                    ]);
                }else {
                    // decimal

                    $amount = $this->faker->numberBetween(15, 99) . "000";
                    $amount = (int)$amount;

                    modelInstance('EmploymentInformation')->create([
                        'employee_id' => $employeeId,
                        'field_name' => $fieldName,
                        'field_value' => json_encode($amount),
                        'effectivity_date' => currentDate(),
                    ]);
                }
            }


            $this->getOutput()->progressAdvance();
        }

        $this->getOutput()->progressFinish();

    }
}
