<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DailyTimeRecordRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DailyTimeRecordCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DailyTimeRecordCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ForceBulkDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;

    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchPayrollPeriodTrait;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\DailyTimeRecord::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dailytimerecord');

        $this->userPermissions();

        $this->exportClass = '\App\Exports\DailyTimeRecordExport';

        $this->enableDetailsRow();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // hide rows/data that payrollPeriod has softDeleted
        $this->crud->query->has('payrollPeriod');
        $this->crud->query->orderBy('date');

        // data table default page length
        $this->crud->setPageLengthMenu([[10, 15, 20,-1],[10, 15, 20,"backpack::crud.all"]]);
        $this->crud->setDefaultPageLength(15);

        $this->showColumns(null, [
            'reg_hour',
            'late',
            'ut',
            'ot',
            'payroll_period_id',
        ]);
        $this->showEmployeeNameColumn();

        // when employee column order is active , add this order too
        $this->addOrderInEmployeeNameColumn('date');
    
        $this->crud->modifyColumn('date', [
            'wrapper'   => [
                'title' => function ($crud, $column, $entry, $related_key) {
                    return daysOfWeekFromDate($entry->date);
                },
            ],
        ]);

        $this->addListColumn('shiftSchedule');
        $this->addListColumn('logs');
        $this->addListColumn('leave');

        $col = 'payroll_period_id';
        $this->crud->addColumn($col);
        $this->showRelationshipColumn($col);
    
        $this->filters();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false); // remove fk column such as: gender_id
        $this->setupListOperation();
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(DailyTimeRecordRequest::class);
        $this->customInputs();
    }

    private function customInputs()
    {
        $this->inputs();
        $this->addSelectEmployeeField();
    }

    private function filters()
    {
        $this->dateRangeFilter('date');
        $this->select2FromArrayFilter(
            'payroll_period_id',
            $this->fetchPayrollPeriodCollection()->sort()->pluck('name', 'id')->toArray()
        );
    }
}

// TODO:: in edit, use hidden field for employee_id, and date, and create input text just to display the employee_id and date field(for show only).
        // TODO:: add shift schedule  field(change shift schedule) in edit.
        // TODO:: TBD add dtr logs modify in edit.
// TODO:: disable order in these columns: Reg Hour, late, UT, OT, POT

// TODO:: reg hour varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)
// TODO:: late varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)
// TODO:: UT varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)
// TODO:: OT varchar hh:mm nullable, display auto computed regHour if value is null(if not null then it was overriden)

// TODO:: POT hh:mm, no migration col, custom col display in list
// TODO:: dont let user process daily time record row data if logs is morethan the shift schedule details,
        // eg. user have double time in.(so instead of 4 logs, dtr_logs has 5), so create validation.

// TODO:: fix columns search logics

// TODO:: https://github.com/winex01/hris/issues/176
