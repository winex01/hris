<?php

namespace App\Http\Controllers\Admin\Operations\PayrollPeriod;

use App\Models\PayrollPeriod;

trait CreateOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as parentStore; }

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();        

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        // create DailyTimeRecord Crud datas
        $this->createDailyTimeRecordCrudData($item);
    
        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    private function createDailyTimeRecordCrudData(PayrollPeriod $payroll)
    {

        // employee Ids where Grouping on selected grouping
        $employeeIds = modelInstance('EmploymentInformation')
                                ->grouping($payroll->grouping_id)
                                ->pluck('employee_id')
                                ->all();

        $datesInPeriod = carbonPeriodInstance($payroll->payroll_start, $payroll->payroll_end);
                                
        foreach ($employeeIds as $empId) {
            foreach ($datesInPeriod as $date) {
                modelInstance('DailyTimeRecord')::create([
                    'employee_id'       => $empId,
                    'date'              => $date,
                    'payroll_period_id' => $payroll->id
                ]);
            }// end foreach $date
        }// end foreach $emp
    
    }
}
// TODO:: when grouping is edited, fix also by TBD, deleting or updating daily time records data
// TODO:: TBD add employee lists in payroll period crud (see ex. in leaveApplication on approvers column)
// TODO:: review all models method boot and inheret parent boot