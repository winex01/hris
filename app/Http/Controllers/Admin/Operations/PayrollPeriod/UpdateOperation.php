<?php

namespace App\Http\Controllers\Admin\Operations\PayrollPeriod;

use App\Models\PayrollPeriod;
use Illuminate\Support\Facades\Route;

trait UpdateOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as parentUpdate; }

    /**
     * Update the specified resource in the database.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        // update the row in the db
        $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
                            $this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        // create/delete DailyTimeRecord Crud datas
        $this->updateDailyTimeRecordCrudData($item);

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    private function updateDailyTimeRecordCrudData(PayrollPeriod $payroll)
    {
        // employee Ids where Grouping on selected grouping
        $employeeIds = modelInstance('EmploymentInformation')
                                ->grouping($payroll->grouping_id)
                                ->pluck('employee_id')
                                ->all();
        
        $datesInPeriod = carbonPeriodInstance($payroll->payroll_start, $payroll->payroll_end);
        
        // delete employee dailyTimeRecords if their grouping is change
        modelInstance('DailyTimeRecord')->whereNotIn('employee_id', $employeeIds)->forceDelete();

        foreach ($employeeIds as $empId) {
            foreach ($datesInPeriod as $date) {
                modelInstance('DailyTimeRecord')::firstOrCreate([
                    'employee_id'       => $empId,
                    'date'              => $date,
                    'payroll_period_id' => $payroll->id
                ]);
            }// end foreach $date
        }// end foreach $emp
    
    }
}
// NOTE:: when grouping is edited, delete data in daily time records where grouping is change, and create newly added
// employee groupings to daily time records data when payroll_period is updated.
// NOTE:: forceDelete daily time records data where not part of grouping anymore.
