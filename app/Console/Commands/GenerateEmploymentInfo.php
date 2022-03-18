<?php

namespace App\Console\Commands;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
            sleep(1);
            $this->getOutput()->progressAdvance();
        }

        $this->getOutput()->progressFinish();

    }
}
