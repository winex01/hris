<?php

namespace App\Http\Controllers\Admin\Operations\DailyTimeRecord;

use Illuminate\Support\Str;

trait ListWithDetailsRowOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation { 
        showDetailsRow as parentShowDetailsRow;
        setupListDefaults as parentSetupListDefaults; 
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupListDefaults()
    {
        $temp = $this->parentSetupListDefaults();

        $this->enableDetailsRow();

        return $temp;
    }

    /**
     * Used with AJAX in the list view (datatables) to show extra information about that row that didn't fit in the table.
     * It defaults to showing some dummy text.
     *
     * @return \Illuminate\View\View
     */
    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('list');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
    
        // custom
        $this->data['customEntry'] = $this->customEntry();

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getDetailsRowView(), $this->data);
    }
    
    private function customEntry()
    {
        $entry = $this->data['entry'];
        
        $data = [];

        $date = '<span title="'.daysOfWeekFromDate($entry->date).'">'.$entry->date.'</span>';

        $data[trans('lang.daily_time_records_details_row_employee')]       = $entry->employee->employeeNameAnchor();
        $data[trans('lang.daily_time_records_details_row_date')]           = $date;
        $data[trans('lang.daily_time_records_details_row_shift_schedule')] = $entry->shift_schedule_list_column;
        $data[trans('lang.daily_time_records_details_row_logs')]           = $entry->logs_list_column;
        $data[trans('lang.daily_time_records_details_row_payroll_period')] = $entry->payrollPeriod->name;
        $data[trans('lang.daily_time_records_details_row_reg_hour')]       = $entry->reg_hour_list_column;
        $data[trans('lang.daily_time_records_details_row_late')]           = $entry->late_list_column;
        $data[trans('lang.daily_time_records_details_row_undertime')]      = $entry->undertime_list_column;
        $data[trans('lang.daily_time_records_details_row_break_excess')]   = $entry->break_excess_list_column;
        $data[trans('lang.daily_time_records_details_row_overtime')]       = $entry->overtime_list_column;

        return $data;
    }

    private function enableDetailsRow()
    {
        $this->crud->enableDetailsRow();
        $this->crud->setDetailsRowView('backpack::crud.details_row.custom_default');
    }
}
